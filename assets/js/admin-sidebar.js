// Sidebar collapse functionality
document.addEventListener('DOMContentLoaded', function() {
	const collapseToggle = document.getElementById('collapseToggle');
	const sidebar = document.getElementById('sidebar');
	const collapseIcon = document.getElementById('collapseIcon');
	
	// Initialize sidebar state from localStorage or default to expanded
	let isCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
	
	function updateSidebarState() {
		if (isCollapsed) {
			sidebar.classList.remove('sidebar-expanded');
			sidebar.classList.add('sidebar-collapsed');
			collapseIcon.classList.remove('fa-chevron-left');
			collapseIcon.classList.add('fa-chevron-right');
		} else {
			sidebar.classList.remove('sidebar-collapsed');
			sidebar.classList.add('sidebar-expanded');
			collapseIcon.classList.remove('fa-chevron-right');
			collapseIcon.classList.add('fa-chevron-left');
		}
		localStorage.setItem('sidebarCollapsed', isCollapsed);
	}
	
	// Toggle sidebar collapse
	if (collapseToggle) {
		collapseToggle.addEventListener('click', function() {
			isCollapsed = !isCollapsed;
			updateSidebarState();
		});
	}
	
	// Initialize sidebar state on page load
	updateSidebarState();
	
	// Submenu toggle functionality
	const submenuToggles = document.querySelectorAll('.submenu-toggle');
	submenuToggles.forEach(toggle => {
		toggle.addEventListener('click', function(e) {
			e.preventDefault();
			const targetId = this.getAttribute('data-target');
			const submenu = document.getElementById(targetId);
			const chevron = this.querySelector('.fa-chevron-down');
			
			if (submenu) {
				// Toggle submenu visibility
				if (submenu.style.maxHeight && submenu.style.maxHeight !== '0px') {
					submenu.style.maxHeight = '0px';
					chevron.style.transform = 'rotate(0deg)';
				} else {
					submenu.style.maxHeight = submenu.scrollHeight + 'px';
					chevron.style.transform = 'rotate(180deg)';
				}
			}
		});
	});
	
	// Mobile sidebar functionality
	const menuToggle = document.getElementById('menuToggle');
	const sidebarOverlay = document.getElementById('sidebarOverlay');
	
	function toggleMobileSidebar() {
		sidebar.classList.toggle('-translate-x-full');
		if (sidebarOverlay) {
			sidebarOverlay.classList.toggle('hidden');
		}
	}
	
	if (menuToggle) {
		menuToggle.addEventListener('click', toggleMobileSidebar);
	}
	
	if (sidebarOverlay) {
		sidebarOverlay.addEventListener('click', toggleMobileSidebar);
	}
	
	// Close mobile sidebar on window resize
	window.addEventListener('resize', function() {
		if (window.innerWidth >= 1024) {
			sidebar.classList.remove('-translate-x-full');
			if (sidebarOverlay) {
				sidebarOverlay.classList.add('hidden');
			}
		}
	});
	
	// Initialize mobile sidebar position
	if (window.innerWidth < 1024) {
		sidebar.classList.add('-translate-x-full');
	}
	
	// Add smooth scrolling to navigation
	const navLinks = document.querySelectorAll('nav a');
	navLinks.forEach(link => {
		link.addEventListener('click', function(e) {
			// Add a small loading state
			this.style.opacity = '0.7';
			setTimeout(() => {
				this.style.opacity = '1';
			}, 200);
		});
	});
});
