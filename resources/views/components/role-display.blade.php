<div class="role-display-container">
    <!-- Sales Roles Section -->
    <div class="role-section">
        <div class="section-header">
            <div class="header-icon sales-icon">
                <i class="fas fa-users"></i>
            </div>
            <h3 class="section-title">Sales Roles</h3>
        </div>
        
        <div class="roles-grid">
            <div class="role-card" data-role="manager">
                <div class="role-icon">
                    <i class="fas fa-briefcase"></i>
                </div>
                <div class="role-content">
                    <div class="role-name">Manager</div>
                    <div class="role-description">Client relationship management</div>
                </div>
                <div class="role-badge manager-badge">
                    <span>Sales</span>
                </div>
            </div>
            
            <div class="role-card" data-role="rop">
                <div class="role-icon">
                    <i class="fas fa-crown"></i>
                </div>
                <div class="role-content">
                    <div class="role-name">ROP</div>
                    <div class="role-description">Sales department head</div>
                </div>
                <div class="role-badge rop-badge">
                    <span>Leadership</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Administrative Roles Section -->
    <div class="role-section">
        <div class="section-header">
            <div class="header-icon admin-icon">
                <i class="fas fa-shield-alt"></i>
            </div>
            <h3 class="section-title">Administrative Roles</h3>
        </div>
        
        <div class="roles-grid">
            <div class="role-card" data-role="admin">
                <div class="role-icon">
                    <i class="fas fa-shield-alt"></i>
                </div>
                <div class="role-content">
                    <div class="role-name">Administrator</div>
                    <div class="role-description">Full system access</div>
                </div>
                <div class="role-badge admin-badge">
                    <span>Admin</span>
                </div>
            </div>
            
            
            
            <div class="role-card" data-role="accountant">
                <div class="role-icon">
                    <i class="fas fa-calculator"></i>
                </div>
                <div class="role-content">
                    <div class="role-name">Accountant</div>
                    <div class="role-description">Financial reporting</div>
                </div>
                <div class="role-badge accountant-badge">
                    <span>Finance</span>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Import Google Fonts */
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');

/* Role Display Container */
.role-display-container {
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    max-width: 800px;
    margin: 0 auto;
    padding: 24px;
    background: #ffffff;
    border-radius: 16px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    border: 1px solid #f1f5f9;
}

/* Role Sections */
.role-section {
    margin-bottom: 32px;
}

.role-section:last-child {
    margin-bottom: 0;
}

/* Section Headers */
.section-header {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 20px;
    padding-bottom: 12px;
    border-bottom: 2px solid #f8fafc;
}

.header-icon {
    width: 40px;
    height: 40px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 16px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.header-icon:hover {
    transform: scale(1.05);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
}

.sales-icon {
    background: linear-gradient(135deg, #1ba4e9 0%, #ac76e3 100%);
}

.admin-icon {
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
}

.section-title {
    font-size: 18px;
    font-weight: 600;
    color: #1e293b;
    margin: 0;
    letter-spacing: -0.025em;
}

/* Roles Grid */
.roles-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 16px;
}

/* Role Cards */
.role-card {
    background: #ffffff;
    border: 2px solid #f1f5f9;
    border-radius: 16px;
    padding: 20px;
    display: flex;
    align-items: center;
    gap: 16px;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    cursor: pointer;
    position: relative;
    overflow: hidden;
}

.role-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(135deg, rgba(102, 126, 234, 0.05) 0%, rgba(118, 75, 162, 0.05) 100%);
    opacity: 0;
    transition: opacity 0.3s ease;
    border-radius: 16px;
}

.role-card:hover {
    transform: translateY(-4px);
    border-color: #e2e8f0;
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.12);
}

.role-card:hover::before {
    opacity: 1;
}

.role-card:active {
    transform: translateY(-2px);
}

/* Role Icons */
.role-icon {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 18px;
    flex-shrink: 0;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    transition: all 0.3s ease;
}

.role-card:hover .role-icon {
    transform: scale(1.1);
    box-shadow: 0 6px 16px rgba(0, 0, 0, 0.2);
}

/* Role Content */
.role-content {
    flex: 1;
    min-width: 0;
}

.role-name {
    font-size: 16px;
    font-weight: 600;
    color: #1e293b;
    margin-bottom: 4px;
    line-height: 1.4;
}

.role-description {
    font-size: 14px;
    color: #64748b;
    line-height: 1.4;
}

/* Role Badges */
.role-badge {
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: white;
    flex-shrink: 0;
    transition: all 0.3s ease;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
}

.role-card:hover .role-badge {
    transform: scale(1.05);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
}

/* Badge Colors */
.manager-badge {
    background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
}

.rop-badge {
    background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
}

.admin-badge {
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
}

 

.accountant-badge {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
}

/* Icon Colors */
.role-card[data-role="manager"] .role-icon {
    background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
}

.role-card[data-role="rop"] .role-icon {
    background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
}

.role-card[data-role="admin"] .role-icon {
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
}

 

.role-card[data-role="accountant"] .role-icon {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
}

/* Click Animation */
.role-card.clicked {
    animation: clickPulse 0.3s ease-out;
}

@keyframes clickPulse {
    0% {
        transform: scale(1);
    }
    50% {
        transform: scale(0.98);
    }
    100% {
        transform: scale(1);
    }
}

/* Glow Effect on Hover */
.role-card:hover {
    box-shadow: 
        0 8px 32px rgba(0, 0, 0, 0.12),
        0 0 0 1px rgba(102, 126, 234, 0.1);
}

/* Responsive Design */
@media (max-width: 768px) {
    .role-display-container {
        padding: 16px;
        margin: 16px;
    }
    
    .roles-grid {
        grid-template-columns: 1fr;
        gap: 12px;
    }
    
    .role-card {
        padding: 16px;
        gap: 12px;
    }
    
    .role-icon {
        width: 40px;
        height: 40px;
        font-size: 16px;
    }
    
    .role-name {
        font-size: 15px;
    }
    
    .role-description {
        font-size: 13px;
    }
    
    .section-title {
        font-size: 16px;
    }
}

@media (max-width: 480px) {
    .role-card {
        flex-direction: column;
        text-align: center;
        gap: 12px;
    }
    
    .role-badge {
        align-self: center;
    }
}

/* Loading Animation */
.role-card.loading {
    opacity: 0.7;
    pointer-events: none;
}

.role-card.loading::after {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 20px;
    height: 20px;
    margin: -10px 0 0 -10px;
    border: 2px solid #e2e8f0;
    border-top: 2px solid #3b82f6;
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

/* Focus States for Accessibility */
.role-card:focus {
    outline: none;
    box-shadow: 
        0 8px 32px rgba(0, 0, 0, 0.12),
        0 0 0 3px rgba(102, 126, 234, 0.3);
}

/* Dark Mode Support */
@media (prefers-color-scheme: dark) {
    .role-display-container {
        background: #1e293b;
        border-color: #334155;
    }
    
    .role-card {
        background: #334155;
        border-color: #475569;
    }
    
    .section-title {
        color: #f1f5f9;
    }
    
    .role-name {
        color: #f1f5f9;
    }
    
    .role-description {
        color: #94a3b8;
    }
    
    .role-card::before {
        background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%);
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const roleCards = document.querySelectorAll('.role-card');
    
    // Click animation
    roleCards.forEach(card => {
        card.addEventListener('click', function() {
            // Remove existing animation class
            this.classList.remove('clicked');
            
            // Add animation class
            this.classList.add('clicked');
            
            // Remove class after animation completes
            setTimeout(() => {
                this.classList.remove('clicked');
            }, 300);
            
            // Optional: Show tooltip or trigger action
            const roleName = this.querySelector('.role-name').textContent;
            // handleRoleClick(roleName); // This function is not defined in the original file
        });
    });
    
    // Hover effects with delay
    roleCards.forEach(card => {
        let hoverTimeout;
        
        card.addEventListener('mouseenter', function() {
            clearTimeout(hoverTimeout);
            this.style.transition = 'all 0.3s cubic-bezier(0.4, 0, 0.2, 1)';
        });
        
        card.addEventListener('mouseleave', function() {
            hoverTimeout = setTimeout(() => {
                this.style.transition = 'all 0.3s cubic-bezier(0.4, 0, 0.2, 1)';
            }, 100);
        });
    });
    
    // Keyboard navigation
    roleCards.forEach(card => {
        card.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                this.click();
            }
        });
        
        // Make cards focusable
        card.setAttribute('tabindex', '0');
    });
    
    // Optional: Add loading state
    function addLoadingState(card) {
        card.classList.add('loading');
        setTimeout(() => {
            card.classList.remove('loading');
        }, 2000);
    }
    
    // Optional: Add ripple effect
    roleCards.forEach(card => {
        card.addEventListener('click', function(e) {
            const ripple = document.createElement('div');
            ripple.style.position = 'absolute';
            ripple.style.borderRadius = '50%';
            ripple.style.background = 'rgba(255, 255, 255, 0.3)';
            ripple.style.transform = 'scale(0)';
            ripple.style.animation = 'ripple 0.6s linear';
            ripple.style.left = (e.clientX - this.offsetLeft) + 'px';
            ripple.style.top = (e.clientY - this.offsetTop) + 'px';
            ripple.style.width = ripple.style.height = '20px';
            
            this.appendChild(ripple);
            
            setTimeout(() => {
                ripple.remove();
            }, 600);
        });
    });
});

// Ripple animation
const style = document.createElement('style');
style.textContent = `
    @keyframes ripple {
        to {
            transform: scale(4);
            opacity: 0;
        }
    }
`;
document.head.appendChild(style);
</script> 