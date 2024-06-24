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

function mostrarErro(element, text) {

    element.classList.add("border-danger");
    element.classList.replace("mb-3", "mb-1");

    let msg = document.createElement("p");

    msg.id = "msgErro" + element.id;

    msg.innerHTML = "<i class='fa fa-circle-info'></i> " + text;

    msg.style = "font-size: 0.85em;";

    msg.classList.add("text-danger");

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

// Eventos

formCadastro.addEventListener("submit", e => {

    e.preventDefault();

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

    let dataAtual = new Date().toJSON().slice(0,10).replace(/-/g, "");
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
            }  else {
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
                formCadastro.submit();
            }
        });
});

modalBody.childNodes.forEach(formElement => {
    if (formElement.nodeName == "INPUT") {
        formElement.addEventListener("focus", () => {
            formElement.classList.remove("border-danger");
        });
    }
});