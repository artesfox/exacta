// Exacta – Talento de Excelencia · JavaScript moderno (sin jQuery)
document.documentElement.classList.remove('no-js');

document.addEventListener('DOMContentLoaded', () => {
  const body = document.body;
  const header = document.querySelector('.header');
  const btnMenu = document.querySelector('.btn-menu');
  const velo = document.querySelector('.velo');
  const menu = document.getElementById('menu');

  /* ---------- Menú móvil lateral ---------- */
  const abrirMenu = (abrir) => {
    body.classList.toggle('menu-abierto', abrir);
    btnMenu?.setAttribute('aria-expanded', String(abrir));
    btnMenu?.setAttribute('aria-label', abrir ? 'Cerrar menú' : 'Abrir menú');
  };

  btnMenu?.addEventListener('click', () => abrirMenu(!body.classList.contains('menu-abierto')));
  velo?.addEventListener('click', () => abrirMenu(false));
  menu?.querySelectorAll('a').forEach((a) => a.addEventListener('click', () => abrirMenu(false)));
  document.addEventListener('keydown', (e) => { if (e.key === 'Escape') abrirMenu(false); });
  window.matchMedia('(min-width: 761px)').addEventListener('change', (e) => { if (e.matches) abrirMenu(false); });

  /* ---------- Cabecera compacta al hacer scroll ---------- */
  const alHacerScroll = () => header?.classList.toggle('scrolled', window.scrollY > 40);
  alHacerScroll();
  window.addEventListener('scroll', alHacerScroll, { passive: true });

  /* ---------- Aparición suave de elementos al hacer scroll ---------- */
  const elementos = document.querySelectorAll('.revelar');
  if ('IntersectionObserver' in window) {
    const observador = new IntersectionObserver((entradas) => {
      entradas.forEach((entrada) => {
        if (entrada.isIntersecting) {
          entrada.target.classList.add('visible');
          observador.unobserve(entrada.target);
        }
      });
    }, { threshold: 0.15, rootMargin: '0px 0px -40px 0px' });
    elementos.forEach((el) => observador.observe(el));
  } else {
    elementos.forEach((el) => el.classList.add('visible'));
  }

  /* ---------- Año actual en el pie ---------- */
  document.querySelectorAll('[data-anio]').forEach((el) => { el.textContent = new Date().getFullYear(); });
});
