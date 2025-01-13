// Responsive Design with JavaScript
(function () {
    function applyResponsiveDesign() {
        const viewportWidth = window.innerWidth;

        if (viewportWidth <= 768) { // Mobile View
            document.body.classList.add('mobile-view');
            document.body.classList.remove('desktop-view');
            
            // Adjust chatbox for mobile
            document.querySelector('.chatbox').style.height = '300px';
            document.querySelector('.chatbox-body').style.height = '200px';
            
           //adjust the games section for mobile
           document.querySelector('.games').style.height = '300';
            
        } else { // Desktop View
            document.body.classList.add('desktop-view');
            document.body.classList.remove('mobile-view');
            
            // Adjust chatbox for desktop
            document.querySelector('.chatbox').style.height = '400px';
            document.querySelector('.chatbox-body').style.height = '300px';
            
            // Show games section for desktop
            const gamesSection = document.querySelector('.games');
            if (gamesSection) gamesSection.style.display = 'flex';
        }
    }

    // Initial call
    applyResponsiveDesign();

    // Reapply on window resize
    window.addEventListener('resize', applyResponsiveDesign);
})();
