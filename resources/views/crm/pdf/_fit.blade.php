<script>
  // Long documents: shrink the page content step by step until it fits on one Letter page.
  (function () {
    var page = document.querySelector('.page');
    var zoom = 1;
    while (page.scrollHeight > page.clientHeight + 1 && zoom > 0.7) {
      zoom -= 0.02;
      page.style.zoom = zoom;
      page.style.width = (8.5 / zoom) + 'in';
      page.style.height = (11 / zoom) + 'in';
    }
  })();
</script>
