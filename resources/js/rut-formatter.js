/**
 * Formateador de RUT chileno con JavaScript puro.
 * No requiere librerías externas.
 */
document.addEventListener('DOMContentLoaded', () => {
  // Busca los campos del formulario en la página.
  const rutVisibleInput = document.getElementById('rut_visible');
  const rutHiddenInput = document.getElementById('rut');

  // Si ambos campos existen, activa el formateador.
  if (rutVisibleInput && rutHiddenInput) {
    
    /**
     * Limpia un string de RUT, dejando solo números y la letra 'K'.
     * @param {string} value - El valor del RUT a limpiar.
     * @returns {string} - El RUT limpio.
     */
    const cleanRut = (value) => {
      return typeof value === 'string' ? value.replace(/[^0-9kK]/g, '').toUpperCase() : '';
    };

    /**
     * Formatea un string de RUT limpio al formato XX.XXX.XXX-X.
     * @param {string} value - El valor del RUT limpio.
     * @returns {string} - El RUT formateado.
     */
    const formatRut = (value) => {
      const clean = cleanRut(value);
      const body = clean.slice(0, -1);
      const verifier = clean.slice(-1);
      
      // Formatea el cuerpo del RUT con puntos.
      const formattedBody = body.replace(/\B(?=(\d{3})+(?!\d))/g, '.');

      return clean.length > 1 ? `${formattedBody}-${verifier}` : clean;
    };

    // --- LÓGICA PRINCIPAL ---
    // Se activa cada vez que el usuario escribe en el campo visible.
    rutVisibleInput.addEventListener('input', (e) => {
      const currentValue = e.target.value;
      
      // 1. Limpia el valor que el usuario está escribiendo.
      const cleanedValue = cleanRut(currentValue);
      
      // 2. Actualiza el campo oculto con el valor limpio (esto es lo que se enviará al servidor).
      rutHiddenInput.value = cleanedValue;
      
      // 3. Formatea el valor y lo muestra de vuelta en el campo visible.
      e.target.value = formatRut(currentValue);
    });
  }
});