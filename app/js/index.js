window.addEventListener('load', async () => {
    document.getElementById("enviarDescriptografia").addEventListener("click", enviarDescriptografia);
    document.getElementById("enviarCriptografia").addEventListener("click", enviarCriptografia);
});

async function enviarCriptografia() {
    try {
        var arquivoCriptografia = document.getElementById("criptografia").files[0];
        if (arquivoCriptografia) {
            enviarArquivoParaAPI(arquivoCriptografia, 'http://localhost/trabalho_final/api/php/criptografar');
        } else {
            throw new Error('Nenhum arquivo selecionado para riptografia.');
        }
    } catch (error) {
        document.getElementById("saidaPaginaHtml").innerHTML = "<div class='alert alert-danger'>" + error.message + "</div>";
    }
}

async function enviarDescriptografia(){
    try{
        var arquivoDescriptografia = document.getElementById("descriptografia").files[0];
        if (arquivoDescriptografia) {
            enviarArquivoParaAPI(arquivoDescriptografia, 'http://localhost/trabalho_final/api/php/descriptografar');
        } else {
            throw new Error('Nenhum arquivo selecionado para descriptografia.');
        }
    } catch (error) {
        document.getElementById("saidaPaginaHtml").innerHTML = "<div class='alert alert-danger'>" + error.message + "</div>";
    }
}

function enviarArquivoParaAPI(file, endpoint, criptografia = true) {
    var formData = new FormData();
    formData.append('file', file);

    if(criptografia)
        formData.append('valorN', document.getElementById("valorNCriptografia").value);
    else
        formData.append('valorN', document.getElementById("valorNDescriptografia").value);

    fetch(endpoint, {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if(criptografia)
            montarEExibirTabelaResultadosCriptografados(data);
        else
            montarEExibirTabelaResultadosDescriptografados(data);
        
    })
    .catch(error => {
        throw new Error(error.message);
    });
}

function montarEExibirTabelaResultadosCriptografados(resultados){
    let elemento = document.getElementById("resultadoCriptografia");
    let html = "<table class='table'>";
    for (const resultado of resultados) {
        html += "<p class='fs-2'>Palavra Criptografada: " +  resultado['palavra'] + "</p> <br />";
        html += "<table class='table'>";
        html += "<thead>";
        html += "<tr>";
        html += "<th scope='col'>Posição</th>";
        html += "<th scope='col'>Bloco</th>";
        html += "</tr>";
        html += "</thead>";
        html += "<tbody>";

        let i = 0;
        for (const result of resultado['blocoCodificado']) {
            html += "<tr>";
            html += "<td>" + i + "</td>";
            html += "<td>" + result + "</td>";
            html += "</tr>";
            i++;
        }
                
        html += "</tbody>";
        html += "</table>";
    }

    elemento.innerHTML = html;
    elemento.style.display = "block";

    document.getElementById("divResultados").style.display = "block";
}

function montarEExibirTabelaResultadosDescriptografados(resultados){
    let elemento = document.getElementById("resultadoDescriptografia");
    let html = "<table class='table'>";
    for (const resultado of resultados) {
        html += "<p class='fs-2'>Bloco para ser Criptografado: " +  resultado['palavra'] + "</p> <br />";
        html += "<table class='table'>";
        html += "<thead>";
        html += "<tr>";
        html += "<th scope='col'>Posição</th>";
        html += "<th scope='col'>Bloco</th>";
        html += "</tr>";
        html += "</thead>";
        html += "<tbody>";

        let i = 0;
        for (const result of resultado['blocoCodificado']) {
            html += "<tr>";
            html += "<td>" + i + "</td>";
            html += "<td>" + result + "</td>";
            html += "</tr>";
            i++;
        }
                
        html += "</tbody>";
        html += "</table>";
    }

    elemento.innerHTML = html;
    elemento.style.display = "block";

    document.getElementById("divResultados").style.display = "block";
}

function limparResultados(){
    document.getElementById("resultadoDescriptografia").innerHTML = "";
    document.getElementById("resultadoCriptografia").innerHTML = "";
}