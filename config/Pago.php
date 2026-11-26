<?php
/**
 * Datos que se incluyen dentro del código QR de pago.
 * Cámbialos por los de tu clínica.
 */
class Pago {
    const CLINICA = 'Veterinaria';   // nombre que aparece en el QR
    const CUENTA  = '';              // opcional: nº de cuenta / referencia para el cobro (ej. '1234567890')
    const MONEDA  = 'Bs';
}
?>
