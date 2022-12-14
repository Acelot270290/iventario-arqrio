$(function () {
    $('.money').mask('#.##0,00', {reverse: true});
    $('.money2').mask('#,##0.00', {reverse: true});
    $('.cep').mask('00000-000');
    $('.cpf').mask('000.000.000-00', {reverse: true});
    $('.cnpj').mask('00.000.000/0000-00', {reverse: true});
    $('.phone_with_ddd').mask('(00) 0000-0000');
    $('.uf').mask('AA');
    $('.dia_vencimento').mask('00');
    $('.mask-produto-qty').mask('00000000');
    $('.codigo_servico_correios').mask('00000');
    $('.selectonfocus').mask("00000000", {selectOnFocus: true});
    $('.card_number').mask('0000 0000 0000 0000');
    $('.card_expire').mask('00/0000');
    $('.card_cvv').mask('000');



    var SPMaskBehavior = function (val) {
        return val.replace(/\D/g, '').length === 11 ? '(00) 00000-0000' : '(00) 0000-00009';
    },
            spOptions = {
                onKeyPress: function (val, e, field, options) {
                    field.mask(SPMaskBehavior.apply({}, arguments), options);
                }
            };

    $('.sp_celphones').mask(SPMaskBehavior, spOptions);
})