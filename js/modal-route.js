/*Script para abrir o modal cadastrar linha*/
const btn_cad = document.querySelector("#btn-cad")
const modal_cad = document.querySelector("dialog")

btn_cad.onclick = function ()
{
    modal_cad.showModal()
}

/*Script para fechar os modais*/
const buttonClose = document.querySelector("dialog button")

buttonClose.onclick = function()
{
    modal_cad.close()
    modal_edit.close()
}
