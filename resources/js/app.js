import * as bootstrap from 'bootstrap';
window.bootstrap = bootstrap;

document.addEventListener('DOMContentLoaded', () => {
    const sidebar  = document.getElementById('sidebar');
    const overlay  = document.getElementById('sidebar-overlay');
    const openBtn  = document.getElementById('sidebar-open');
    const closeBtn = document.getElementById('sidebar-close');

    if (!sidebar) return;

    const openSidebar = () => {
        sidebar.classList.add('show');
        overlay?.classList.add('show');
        document.body.style.overflow = 'hidden';
    };

    const closeSidebar = () => {
        sidebar.classList.remove('show');
        overlay?.classList.remove('show');
        document.body.style.overflow = '';
    };

    openBtn?.addEventListener('click', openSidebar);
    closeBtn?.addEventListener('click', closeSidebar);
    overlay?.addEventListener('click', closeSidebar);

    window.addEventListener('resize', () => {
        if (window.innerWidth >= 992) {
            sidebar.classList.remove('show');
            overlay?.classList.remove('show');
            document.body.style.overflow = '';
        }
    });

    // KPI Number Animation
    const animateKPI = () => {
        const kpiElements = document.querySelectorAll('.kpi-animate');
        
        kpiElements.forEach((element, index) => {
            const finalValue = parseInt(element.textContent.replace(/,/g, '')) || 0;
            const duration = 1500;
            const startTime = performance.now();
   
            const updateNumber = (currentTime) => {
                const elapsed = currentTime - startTime;
                const progress = Math.min(elapsed / duration, 1);
                
                // Easing function for smooth animation
                const easeOutQuart = 1 - Math.pow(1 - progress, 4);
                const currentValue = Math.floor(finalValue * easeOutQuart);
                
                element.textContent = currentValue.toLocaleString('ar-SA');
                
                if (progress < 1) {
                    requestAnimationFrame(updateNumber);
                } else {
                    element.textContent = finalValue.toLocaleString('ar-SA');
                }
            };
            
            // Stagger animations
            setTimeout(() => {
                requestAnimationFrame(updateNumber);
            }, index * 100);
        });
    };

    // Intersection Observer for scroll animations
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, observerOptions);

    // Observe all premium cards and stat cards
    document.querySelectorAll('.premium-card, .stat-card, .brutal-card').forEach(card => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        card.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
        observer.observe(card);
    });

    // Initialize KPI animation
    setTimeout(animateKPI, 300);

    // Card hover effects with subtle scale
    document.querySelectorAll('.premium-card, .stat-card').forEach(card => {
        card.addEventListener('mouseenter', () => {
            card.style.transform = 'translateY(-4px) scale(1.01)';
        });
        
        card.addEventListener('mouseleave', () => {
            card.style.transform = 'translateY(0) scale(1)';
        });
    });

    // Chart bar hover animation
    document.querySelectorAll('.chart-bar').forEach(bar => {
        bar.addEventListener('mouseenter', () => {
            bar.style.filter = 'brightness(1.1)';
        });
        
        bar.addEventListener('mouseleave', () => {
            bar.style.filter = 'brightness(1)';
        });
    });

    // Search box focus animation
    const searchBox = document.querySelector('.search-box');
    if (searchBox) {
        const searchInput = searchBox.querySelector('input');
        
        searchInput.addEventListener('focus', () => {
            searchBox.style.transform = 'scale(1.02)';
        });
        
        searchInput.addEventListener('blur', () => {
            searchBox.style.transform = 'scale(1)';
        });
    }

    // Button ripple effect
    document.querySelectorAll('.btn').forEach(button => {
        button.addEventListener('click', function(e) {
            const rect = this.getBoundingClientRect();
            const x = e.clientX - rect.left;
            const y = e.clientY - rect.top;
            
            const ripple = document.createElement('span');
            ripple.style.cssText = `
                position: absolute;
                background: rgba(255, 255, 255, 0.3);
                border-radius: 50%;
                transform: scale(0);
                animation: ripple 0.6s linear;
                left: ${x}px;
                top: ${y}px;
                width: 100px;
                height: 100px;
                margin-left: -50px;
                margin-top: -50px;
            `;
            
            this.style.position = 'relative';
            this.style.overflow = 'hidden';
            this.appendChild(ripple);
            
            setTimeout(() => ripple.remove(), 600);
        });
    });

    // Add ripple animation keyframes
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
});
