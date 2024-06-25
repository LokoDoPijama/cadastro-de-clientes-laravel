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

// Alert
const myAlert = document.querySelector(".alert");
var alertDone = true;

const formsDeletar = document.querySelectorAll(".formDeletar");

/*
A Fazer:

- Mensagem de confirmação ao excluir, com Modals
- Polir mais algumas coisas tomando crud_controle_clientes como referência
*/

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

        inputCodigo.value = codigo;

        ttbNome.value = jsonCliente.nome;

        inputData.value = jsonCliente.dataNasc;

        ttbCep.value = jsonCliente.cep;

        btnConfirmarModal.innerText = "Confirmar";
    }

    formCadastro.setAttribute("action", action);

    modalObj.show();
}

function mostrarErro(element, text) {

    element.classList.add("border-danger");
    element.classList.replace("mb-3", "mb-1");

    let msg = document.createElement("p");

    msg.id = "msgErro" + element.id;

    msg.innerHTML = "<i class='fa fa-circle-info'></i> " + text;

    msg.style = "font-size: 0.85em;";

    msg.classList.add("msgErro", "text-danger");

    msgExistente = document.querySelector("#msgErro" + element.id);

    if (!msgExistente) {
        element.parentNode.insertBefore(msg, element.nextSibling);
    } else {
        msgExistente.innerHTML = msg.innerHTML;
    }
}

function removerErro(element) {
    element.classList.remove("border-danger");
    element.classList.replace("mb-1", "mb-3");

    let msg = document.querySelector("#msgErro" + element.id);

    if (msg !== null) {
        msg.remove();
    }
}

function mostrarAlert($contexto) {

    const disposableAlert = myAlert.cloneNode(true);

    if ($contexto == "clienteCadastrado") {
        disposableAlert.classList.remove("alert-danger");
        disposableAlert.classList.add("alert-success");

        disposableAlert.innerHTML = '<i class="fa fa-circle-info me-1"></i> Você cadastrou um cliente';
    } else if ($contexto == "clienteEditado") {
        disposableAlert.classList.remove("alert-danger");
        disposableAlert.classList.add("alert-success");

        disposableAlert.innerHTML = '<i class="fa fa-circle-info me-1"></i> Você editou um cliente';
    } else if ($contexto == "clienteDeletado") {
        disposableAlert.classList.remove("alert-success");
        disposableAlert.classList.add("alert-danger");

        disposableAlert.innerHTML = '<i class="fa fa-trash me-1"></i> Você excluiu um cadastro';
    
    }

    document.body.insertBefore(disposableAlert, document.querySelector(".container"));

    const alertObj = new bootstrap.Alert(disposableAlert);

    disposableAlert.classList.remove("d-none");

    setTimeout(() => {

        alertObj.close();

    }, 2000);
}

// Lógica avulsa


if (!window.performance.getEntriesByType("navigation").map((nav) => nav.type).includes("reload")) { // Verifica se a página não foi recarregada

    if (sessionStorage.getItem("clienteCadastrado") == "true") {
        mostrarAlert("clienteCadastrado");
        sessionStorage.setItem("clienteCadastrado", "false");
    }

    if (sessionStorage.getItem("clienteEditado") == "true") {
        mostrarAlert("clienteEditado");
        sessionStorage.setItem("clienteEditado", "false");
    }

    if (sessionStorage.getItem("clienteDeletado") == "true") {
        mostrarAlert("clienteDeletado");
        sessionStorage.setItem("clienteDeletado", "false");
    }
}

// Eventos

modal.addEventListener("hidden.bs.modal", () => {
    formCadastro.reset();

    modalBody.childNodes.forEach(formElement => {
        if (formElement.nodeName == "INPUT") {
            formElement.removeAttribute("value");
            formElement.classList.remove("border-danger");
            formElement.classList.replace("mb-1", "mb-3");
        }
    });

    let msgs = document.querySelectorAll(".msgErro");

    msgs.forEach(msg => {
        msg.remove();
    })
});

formCadastro.addEventListener("submit", e => {

    e.preventDefault();

    let formAction = formCadastro.getAttribute("action").split("/")[3];

    let enviarForm = true;

    // Verificando se o Nome é válido

    if (ttbNome.value.length == 0) {
        enviarForm = false;
        mostrarErro(ttbNome, "O campo Nome não pode estar vazio");
    } else if (ttbNome.value.length > 120) {
        enviarForm = false;
        mostrarErro(ttbNome, "O campo Nome não deve ser maior que 120 caracteres");
    } else {
        removerErro(ttbNome);
    }

    // Verificando se a data é válida

    let dataAtual = new Date().toJSON().slice(0, 10).replace(/-/g, "");
    dataAtual = parseInt(dataAtual, 10);

    let dataInserida = inputData.value.replace(/-/g, "");
    dataInserida = parseInt(dataInserida, 10);

    if (inputData.value === "") {
        enviarForm = false;
        mostrarErro(inputData, "O campo Data não pode estar vazio");
    } else if (dataInserida > dataAtual) {
        enviarForm = false;
        mostrarErro(inputData, "Insira uma data válida");
    } else {
        removerErro(inputData);
    }

    // Verificando se o CEP é válido

    let cep = ttbCep.value;

    var valido;

    fetch("https://viacep.com.br/ws/" + cep + "/json")
        .then(dados => dados.json())
        .then(dados => {
            if (dados.erro == "true") {
                valido = false;
            } else {
                valido = true;
            }
        })
        .catch(() => {
            valido = false;
        })
        .finally(() => {
            if (valido) {
                cep = cep.replace("-", "");
                ttbCep.value = cep;
                removerErro(ttbCep);
            } else {
                enviarForm = false;

                if (ttbCep.value === "") {
                    mostrarErro(ttbCep, "O campo CEP não pode estar vazio");
                } else {
                    mostrarErro(ttbCep, "Digite um CEP válido");
                }
            }

            if (enviarForm) {
                if (formAction == "cadastrarRoute") {
                    sessionStorage.setItem("clienteCadastrado", "true");
                } else if (formAction == "editarRoute") {
                    sessionStorage.setItem("clienteEditado", "true");
                }
                
                formCadastro.submit();
            }
        });
});

formsDeletar.forEach(formDeletar => {
    formDeletar.addEventListener("submit", () => {
        sessionStorage.setItem("clienteDeletado", "true");
    });
});

modalBody.childNodes.forEach(formElement => {
    if (formElement.nodeName == "INPUT") {
        formElement.addEventListener("focus", () => {
            formElement.classList.remove("border-danger");
        });
    }
});