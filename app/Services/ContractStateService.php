<?php

namespace App\Services;

use App\Models\Contract;
use App\Models\User;
use App\Models\Approval;
use App\Models\ContractChange;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ContractStateService
{
    /**
     * Отправить договор на рассмотрение РОП
     */
    public function submitToRop(Contract $contract, User $actor): bool
    {
        if (!$contract->canPerformAction('submit_to_rop', $actor)) {
            throw new \Exception('Недостаточно прав для отправки на рассмотрение РОП');
        }

        return DB::transaction(function () use ($contract, $actor) {
            // Обновляем статус
            $contract->status = Contract::STATUS_PENDING_ROP;
            $contract->version++;
            $contract->current_reviewer_id = $this->findRopForBranch($contract->branch_id);
            $contract->save();

            // Создаем запись об одобрении
            Approval::create([
                'contract_id' => $contract->id,
                'from_role' => $actor->role,
                'to_role' => 'rop',
                'action' => 'submit',
                'comment' => 'Отправлен на рассмотрение РОП',
                'created_by' => $actor->id,
            ]);

            // Логируем изменение статуса
            ContractChange::create([
                'contract_id' => $contract->id,
                'user_id' => $actor->id,
                'role' => $actor->role,
                'field' => 'status',
                'old_value' => Contract::STATUS_DRAFT,
                'new_value' => Contract::STATUS_PENDING_ROP,
                'version_from' => $contract->version - 1,
                'version_to' => $contract->version,
                'changed_at' => now(),
            ]);

            Log::info("Contract {$contract->contract_number} submitted to ROP by {$actor->name}");

            return true;
        });
    }

    /**
     * Отправить договор на рассмотрение бухгалтера
     */
    public function submitToAccountant(Contract $contract, User $actor): bool
    {
        if (!$contract->canPerformAction('submit_to_accountant', $actor)) {
            throw new \Exception('Недостаточно прав для отправки на рассмотрение бухгалтера');
        }

        return DB::transaction(function () use ($contract, $actor) {
            // Обновляем статус
            $contract->status = Contract::STATUS_PENDING_ACCOUNTANT;
            $contract->version++;
            $contract->current_reviewer_id = $this->findAccountant();
            $contract->save();

            // Создаем запись об одобрении
            Approval::create([
                'contract_id' => $contract->id,
                'from_role' => $actor->role,
                'to_role' => 'accountant',
                'action' => 'submit',
                'comment' => 'Отправлен на рассмотрение бухгалтера',
                'created_by' => $actor->id,
            ]);

            // Логируем изменение статуса
            ContractChange::create([
                'contract_id' => $contract->id,
                'user_id' => $actor->id,
                'role' => $actor->role,
                'field' => 'status',
                'old_value' => Contract::STATUS_PENDING_ROP,
                'new_value' => Contract::STATUS_PENDING_ACCOUNTANT,
                'version_from' => $contract->version - 1,
                'version_to' => $contract->version,
                'changed_at' => now(),
            ]);

            Log::info("Contract {$contract->contract_number} submitted to Accountant by {$actor->name}");

            return true;
        });
    }

    /**
     * Одобрить договор
     */
    public function approve(Contract $contract, User $actor, ?string $comment = null): bool
    {
        if (!$contract->canPerformAction('approve', $actor)) {
            throw new \Exception('Недостаточно прав для одобрения договора');
        }

        return DB::transaction(function () use ($contract, $actor, $comment) {
            // Обновляем статус
            $contract->status = Contract::STATUS_APPROVED;
            $contract->version++;
            $contract->current_reviewer_id = null;
            $contract->save();

            // Создаем запись об одобрении
            Approval::create([
                'contract_id' => $contract->id,
                'from_role' => $actor->role,
                'to_role' => 'approved',
                'action' => 'approve',
                'comment' => $comment ?? 'Договор одобрен',
                'created_by' => $actor->id,
            ]);

            // Логируем изменение статуса
            ContractChange::create([
                'contract_id' => $contract->id,
                'user_id' => $actor->id,
                'role' => $actor->role,
                'field' => 'status',
                'old_value' => Contract::STATUS_PENDING_ACCOUNTANT,
                'new_value' => Contract::STATUS_APPROVED,
                'version_from' => $contract->version - 1,
                'version_to' => $contract->version,
                'changed_at' => now(),
            ]);

            Log::info("Contract {$contract->contract_number} approved by {$actor->name}");

            return true;
        });
    }

    /**
     * Отклонить договор
     */
    public function reject(Contract $contract, User $actor, string $comment): bool
    {
        if (!$contract->canPerformAction('reject', $actor)) {
            throw new \Exception('Недостаточно прав для отклонения договора');
        }

        if (empty($comment)) {
            throw new \Exception('Комментарий обязателен при отклонении');
        }

        return DB::transaction(function () use ($contract, $actor, $comment) {
            // Обновляем статус
            $contract->status = Contract::STATUS_REJECTED;
            $contract->version++;
            $contract->current_reviewer_id = $this->findRopForBranch($contract->branch_id);
            $contract->save();

            // Создаем запись об отклонении
            Approval::create([
                'contract_id' => $contract->id,
                'from_role' => $actor->role,
                'to_role' => 'rop',
                'action' => 'reject',
                'comment' => $comment,
                'created_by' => $actor->id,
            ]);

            // Логируем изменение статуса
            ContractChange::create([
                'contract_id' => $contract->id,
                'user_id' => $actor->id,
                'role' => $actor->role,
                'field' => 'status',
                'old_value' => Contract::STATUS_PENDING_ACCOUNTANT,
                'new_value' => Contract::STATUS_REJECTED,
                'version_from' => $contract->version - 1,
                'version_to' => $contract->version,
                'changed_at' => now(),
            ]);

            Log::info("Contract {$contract->contract_number} rejected by {$actor->name}: {$comment}");

            return true;
        });
    }

    /**
     * Приостановить договор
     */
    public function hold(Contract $contract, User $actor, string $comment): bool
    {
        if (!$contract->canPerformAction('hold', $actor)) {
            throw new \Exception('Недостаточно прав для приостановки договора');
        }

        if (empty($comment)) {
            throw new \Exception('Комментарий обязателен при приостановке');
        }

        return DB::transaction(function () use ($contract, $actor, $comment) {
            // Обновляем статус
            $contract->status = Contract::STATUS_ON_HOLD;
            $contract->version++;
            $contract->current_reviewer_id = $actor->id; // Остается у бухгалтера
            $contract->save();

            // Создаем запись о приостановке
            Approval::create([
                'contract_id' => $contract->id,
                'from_role' => $actor->role,
                'to_role' => $actor->role,
                'action' => 'hold',
                'comment' => $comment,
                'created_by' => $actor->id,
            ]);

            // Логируем изменение статуса
            ContractChange::create([
                'contract_id' => $contract->id,
                'user_id' => $actor->id,
                'role' => $actor->role,
                'field' => 'status',
                'old_value' => Contract::STATUS_PENDING_ACCOUNTANT,
                'new_value' => Contract::STATUS_ON_HOLD,
                'version_from' => $contract->version - 1,
                'version_to' => $contract->version,
                'changed_at' => now(),
            ]);

            Log::info("Contract {$contract->contract_number} held by {$actor->name}: {$comment}");

            return true;
        });
    }

    /**
     * Вернуть на доработку
     */
    public function returnForRevision(Contract $contract, User $actor, string $comment): bool
    {
        if (!$contract->canPerformAction('return', $actor)) {
            throw new \Exception('Недостаточно прав для возврата на доработку');
        }

        if (empty($comment)) {
            throw new \Exception('Комментарий обязателен при возврате на доработку');
        }

        return DB::transaction(function () use ($contract, $actor, $comment) {
            // Обновляем статус
            $contract->status = Contract::STATUS_PENDING_ROP;
            $contract->version++;
            $contract->current_reviewer_id = $this->findRopForBranch($contract->branch_id);
            $contract->save();

            // Создаем запись о возврате
            Approval::create([
                'contract_id' => $contract->id,
                'from_role' => $actor->role,
                'to_role' => 'rop',
                'action' => 'return',
                'comment' => $comment,
                'created_by' => $actor->id,
            ]);

            // Логируем изменение статуса
            ContractChange::create([
                'contract_id' => $contract->id,
                'user_id' => $actor->id,
                'role' => $actor->role,
                'field' => 'status',
                'old_value' => Contract::STATUS_PENDING_ACCOUNTANT,
                'new_value' => Contract::STATUS_PENDING_ROP,
                'version_from' => $contract->version - 1,
                'version_to' => $contract->version,
                'changed_at' => now(),
            ]);

            Log::info("Contract {$contract->contract_number} returned for revision by {$actor->name}: {$comment}");

            return true;
        });
    }

    /**
     * Логировать изменения полей договора
     */
    public function logChanges(Contract $contract, array $changes, User $actor): void
    {
        $hasFinancialChanges = false;

        foreach ($changes as $field => $change) {
            ContractChange::create([
                'contract_id' => $contract->id,
                'user_id' => $actor->id,
                'role' => $actor->role,
                'field' => $field,
                'old_value' => $change['old'],
                'new_value' => $change['new'],
                'version_from' => $contract->version,
                'version_to' => $contract->version + 1,
                'changed_at' => now(),
            ]);

            // Проверяем, есть ли изменения в финансовых полях
            if (Contract::isFinancialField($field)) {
                $hasFinancialChanges = true;
            }
        }

        // Если есть изменения в финансовых полях, отправляем на повторное рассмотрение бухгалтера
        if ($hasFinancialChanges && $contract->status === Contract::STATUS_APPROVED) {
            $contract->status = Contract::STATUS_PENDING_ACCOUNTANT;
            $contract->current_reviewer_id = $this->findAccountant();
            
            // Создаем запись об изменении статуса
            ContractChange::create([
                'contract_id' => $contract->id,
                'user_id' => $actor->id,
                'role' => $actor->role,
                'field' => 'status',
                'old_value' => Contract::STATUS_APPROVED,
                'new_value' => Contract::STATUS_PENDING_ACCOUNTANT,
                'version_from' => $contract->version,
                'version_to' => $contract->version + 1,
                'changed_at' => now(),
            ]);
        }

        $contract->version++;
        $contract->save();
    }

    /**
     * Найти РОП для филиала
     */
    private function findRopForBranch(int $branchId): ?int
    {
        $rop = User::where('role', 'rop')
                   ->where('branch_id', $branchId)
                   ->first();

        return $rop ? $rop->id : null;
    }

    /**
     * Найти бухгалтера
     */
    private function findAccountant(): ?int
    {
        $accountant = User::where('role', 'accountant')->first();
        return $accountant ? $accountant->id : null;
    }
}
