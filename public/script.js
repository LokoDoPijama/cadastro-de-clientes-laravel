const btnCadastrar = document.querySelector("#btnCadastrar");

// Modal
const modal = document.querySelector("#modalForm");
const modalTitle = document.querySelector(".modal-title");
const modalBody = document.querySelector(".modal-body");
const modalObj = new bootstrap.Modal(modal);
const btnConfirmarModal = document.querySelector("#btnConfirmarModal");
const formCadastro = document.querySelector("#formCadastro");
const inputCodigo = document.querySelector("#inputCodigo");
const ttbNome = document.querySelector("#ttbNome");
const inputData = document.querySelector("#inputData");
const ttbCep = document.querySelector("#ttbCep");


// Funções

function mostrarModal(contexto, codigo) {

    let action = formCadastro.getAttribute("action").split("/");

    if (contexto == 'cadastro') {
        modalTitle.textContent = "Cadastro de Cliente";

        action[3] = "cadastrarRoute";
        action = action.join("/");

        btnConfirmarModal.innerText = "Cadastrar";
    } else if (contexto == 'editar') {
        
        let jsonCliente = JSON.parse(document.querySelector("#jsonCliente" + codigo).innerText);
        
        modalTitle.textContent = "Editar Registro";

        action[3] = "editarRoute";
        action = action.join("/");

        inputCodigo.setAttribute("value", codigo);

        ttbNome.setAttribute("value", jsonCliente.nome);

        inputData.setAttribute("value", jsonCliente.dataNasc);

        ttbCep.setAttribute("value", jsonCliente.cep);

        btnConfirmarModal.innerText = "Confirmar";
    }

    formCadastro.setAttribute("action", action);

    modalObj.show();

}