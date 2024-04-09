<div class="modal fade" id="staticBackdropRegister" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header" style="background-color: #58af9b; color:white;">
        <h1 class="modal-title fs-5" id="staticBackdropLabel"><i class="me-2 fas fa-users"></i>Cadastrar Funcionario</h1>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>



      <div class="modal-body">
        <form id="insert_employee_form" method="POST" action="#">
          <div class="row">
          <div class="msg mx-auto"></div>
          
            <div class="col-md-12">
              <label class="form-label" for="nome_funcionario">Nome Completo</label>
              <input type="text" name="nome_funcionario" class="form-control" placeholder="Nome">
            </div>

            <div class="col-md-7 mt-3">
              <label class="form-label" for="email">Endereço de Email</label>
              <input type="email" name="email" class="form-control" placeholder="exemple@gmail.com">
            </div>
            <div class="col-md-5 mt-3">
              <label class="form-label" for="telefone">Telefone</label>
              <input id="telefoneInput" type="text" name="telefone" class="form-control" placeholder="(__) _-____-____">
            </div>

            <div class="col-md-6 mt-3">
              <label class="form-label" for="cpf">CPF</label>
              <input id="cpfInput" type="text" name="cpf" class="form-control" placeholder="___.___.___-__">
            </div>

            <div class="col-md-6 mt-3">
              <label class="form-label" for="genero">Gênero</label>
              <select class="form-select" name="genero" aria-label="Default select example">
                <option value="" selected>Selecione um gênero...</option>
                <option value="M">Masculino</option>
                <option value="F">Feminino</option>
              </select>
            </div>



            <hr class="border opacity-50 mx-auto mt-5" style="max-width: 90%;">
            <div class="col-md-8 mt-3">
              <label class="form-label" for="empresa">Empresa</label>
              <select id="employeeCompanySelect" class="form-select" name="empresa" aria-label="Default select example">
                <option value="" selected>Selecione uma empresa...</option>
                <?php foreach ($empresasData as $empresa) {
                  $token = encrypt_id($empresa['id'], $encryptionKey, $signatureKey); ?>
                  <option value="<?php echo $token; ?>"><?php echo $empresa['nome_fantasia']; ?></option>
                <?php } ?>
              </select>
            </div>


            <div class="col-md-4 mt-3">
              <label class="form-label" for="registro">Numero de Registro</label>
              <input type="number" name="numero_registro_empresa" class="form-control" placeholder="">
            </div>
          </div>

          <div class="row positionAndDepartmentContent"></div>
        </form>

      </div>
      <div class="modal-footer d-flex justify-content-end">
        <button type="submit" id="confirmarInsertEmployeeBtn" class="btn btn-login">Confirmar</button>
      </div>
    </div>
  </div>
</div>




<div class="modal fade" id="modalDeleteEmployee" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalTitleId">Confirmar exclusão</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="container-fluid">
          <p>Você realmente deseja excluir o funcionario(a) <span id="employeeName"></span>?</p>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button class="btn btn-danger" id="confirmDeleteBtn">Confirmar</button>
      </div>
    </div>
  </div>
</div>





<div class="modal fade" id="modalUpdateEmployee" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalTitleId">Confirmar Edição em <span id="funcionarioNome"></span></h5>
        <button type="button" class="btn-close btn-close-update" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="container-fluid">
          <p>Você realmente deseja editar de <span class="fw-bold" id="campoNome"></span> para <span class="fw-bold" id=campoValue></span>?</p>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary btn-close-update" data-bs-dismiss="modal">Cancelar</button>
        <button class="btn btn-danger" id="confirmarUpdateBtn">Confirmar</button>
      </div>
    </div>
  </div>
</div>