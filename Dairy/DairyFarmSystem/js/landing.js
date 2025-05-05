// Add animation to buttons
window.onload = function () {
    const btn = document.querySelector('.btn-primary');

    btn.addEventListener('mouseover', () => {
        btn.style.transform = 'scale(1.1)';
        btn.style.transition = 'transform 0.2s';
    });

    btn.addEventListener('mouseout', () => {
        btn.style.transform = 'scale(1)';
    });
};
