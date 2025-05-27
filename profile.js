const openBtn = document.getElementById('openSidebar');
    const closeBtn = document.getElementById('closeSidebar');
    const sidebar = document.getElementById('sidebar');

    openBtn.addEventListener('click', () => {
      sidebar.classList.add('open');
    });

    closeBtn.addEventListener('click', () => {
      sidebar.classList.remove('open');
    });