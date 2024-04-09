<div class="modal fade" id="staticBackdropRegister" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header" style="background-color: #58af9b; color:white;">
        <h1 class="modal-title fs-5" id="staticBackdropLabel"><i class="me-2 fas fa-users"></i>Cadastrar Empresa</h1>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body">
        <form id="company_insert" method="POST">
          <div class="row">
            <div class="msg mx-auto"></div>



            <label class="form-label" for="cnpj">CNPJ</label>
            <div class="col-md-12 d-flex aling-items-center">
              <input id="cnpjInput" type="text" name="cnpj" class="form-control me-3" placeholder="__.___.___/____-__">
              <button class="btn btn-outline-secondary" id="consultarcnpj">Consultar</button>
            </div>

            <div class="col-md-6 mt-4">
              <label class="form-label" for="razao_social">Razao Social</label>
              <input id="razaoSocialInput" type="text" name="razao_social" class="form-control" placeholder="Digite a razão social da empresa">
            </div>

            <div class="col-md-6 mt-4">
              <label class="form-label" for="">Nome Fantasia</label>
              <input id="nomeFantasiaInput" type="text" name="nome_fantasia" class="form-control" placeholder="Digite o nome fantasia da empresa">
            </div>

            <div class="col-md-6 mt-4">
              <label class="form-label" for="telefone">Telefone</label>
              <input id="telefoneInput" type="text" name="telefone" class="form-control" placeholder="(__) _-____-____">
            </div>
            <div class="col-md-6 mt-4">
              <label class="form-label" for="email">Email</label>
              <input id="emailInput" type="email" name="email" class="form-control" placeholder="example@gmail.com">
            </div>

          </div>
        </form>
      </div>
      <div class="modal-footer d-flex justify-content-end mt-5">
        <button type="submit" class="btn btn-login" id="confirmarInsertCompanyBtn">Confirmar</button>
      </div>
    </div>
  </div>
</div>





<div class="modal fade" id="modalUpdateCompany" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalTitleId">Confirmar Edição em <span id="companyName"></span></h5>
        <button type="button" class="btn-close close-update-btn" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="container-fluid">
          <p>Você realmente deseja editar <span id="campName"></span> para <span id=campValue></span>?</p>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary close-update-btn" data-bs-dismiss="modal">Cancelar</button>
        <button class="btn btn-danger" id="confirmCompanyUpdateBtn">Confirmar</button>
      </div>
    </div>
  </div>
</div>


<div class="modal fade" id="modalDeleteCompany" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title text-danger" id="modalTitleId">Confirmar exclusão</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="container-fluid">
          <p>Você realmente deseja excluir a empresa: <span class="fw-bold" id="companyName"></span></p>
          <hr>
          <p class="text-danger">Este procedimento resultará na <span class="fw-bold">remoção de todos os cargos,
              departamentos e funcionários</span> associados à empresa<span class="fw-bold">,essa ação é IRREVERSÍVEL.</span></p>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button class="btn btn-danger" id="confirmDeleteCompanyBtn">Confirmar</button>
      </div>
    </div>
  </div>
</div>


<div id="modalPositionAndDepartment"></div>
<div id="dynamicModalContent"></div>