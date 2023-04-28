<!doctype html>
<html lang="es">
  <head>
    <meta charset="utf-8">
    <title>Votaciones</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="images/favicon.ico" rel="shortcut icon">
    <link href="css/estilos.css" rel="stylesheet" type="text/css" />
    <link href="libs/sweetalert2/sweetalert2.min.css" rel="stylesheet" type="text/css" />
    <link href="libs/alertify-js/css/alertify.min.css" rel="stylesheet" type="text/css" />
  </head>
  <body>
    <h1>Votaciones</h1>

    <div class="contenedor">
      <div class="seccion secc_formulario">
        <h2><i class="fa-solid fa-user fa-lg"></i> Formulario de Votación</h2>
        <form name="form_votacion" method="post" id="form_votacion" role="form">
          <div class="form_contenedor">
            <div class="form_fila">
              <div class="form_columna_L">
                <label for="txt_nombre">Nombre</label>
              </div>
              <div class="form_columna_R">
                <input type="text" name="txt_nombre" id="txt_nombre" maxlength="100" tabindex="1">
              </div>
            </div>
            <div class="form_fila">
              <div class="form_columna_L">
                <label for="txt_apellido">Apellido</label>
              </div>
              <div class="form_columna_R">
                <input type="text" name="txt_apellido" id="txt_apellido" maxlength="100" tabindex="2">
              </div>
            </div>
            <div class="form_fila">
              <div class="form_columna_L">
                <label for="txt_alias">Alias</label>
              </div>
              <div class="form_columna_R">
                <input type="text" name="txt_alias" id="txt_alias" maxlength="30" tabindex="3">
              </div>
            </div>
            <div class="form_fila">
              <div class="form_columna_L">
                <label for="txt_rut">RUT</label>
              </div>
              <div class="form_columna_R">
                <input type="text" name="txt_rut" id="txt_rut" maxlength="12" tabindex="4" class="formato_rut" placeholder="Ejemplo : 15.129.202-K">
              </div>
            </div>
            <div class="form_fila">
              <div class="form_columna_L">
                <label for="txt_email">Correo Electrónico</label>
              </div>
              <div class="form_columna_R">
                <input type="text" name="txt_email" id="txt_email" maxlength="100" tabindex="5" placeholder="Ejemplo : persona@correo.com">
              </div>
            </div>
            <div class="form_fila">
              <div class="form_columna_L">
                <label for="sel_region">Región</label>
              </div>
              <div class="form_columna_R">
                <select name="sel_region" id="sel_region" tabindex="6">
                  <option value="">&nbsp;</option>
                </select>
              </div>
            </div>
            <div class="form_fila">
              <div class="form_columna_L">
                <label for="sel_comuna">Comuna</label>
              </div>
              <div class="form_columna_R">
                <input type="hidden" name="hf_provincia" id="hf_provincia" value="">
                <select name="sel_comuna" id="sel_comuna" tabindex="7">
                  <option value="">&nbsp;</option>
                </select>
              </div>
            </div>
            <div class="form_fila">
              <div class="form_columna_L">
                <label for="sel_candidato">Candidato</label>
              </div>
              <div class="form_columna_R">
                <select name="sel_candidato" id="sel_candidato" tabindex="8">
                  <option value="">&nbsp;</option>
                </select>
              </div>
            </div>
            <div class="form_fila">
              <div class="form_columna_L">
                <label>¿Cómo se enteró de nosotros?</label>
              </div>
              <div class="form_columna_R">
                <label for="chk_desde_web"><input type="checkbox" name="chk_desde_web" value="1"> Web</label>
                <label for="chk_desde_tv"><input type="checkbox" name="chk_desde_tv" value="1"> TV</label>
                <label for="chk_desde_rrss"><input type="checkbox" name="chk_desde_rrss" value="1"> Redes Sociales</label>
                <label for="chk_desde_amigo"><input type="checkbox" name="chk_desde_amigo" value="1"> Amigo</label>
              </div>
            </div>
            <div class="form_fila">
              <div class="form_columna_L">&nbsp;</div>
              <div class="form_columna_R">
                  <button type="button" id="btn_enviar" tabindex="9">Votar</button>
              </div>
            </div>
          </div>
        </form>
      </div>


      <div class="seccion secc_resultados">
        <h2><i class="fa-solid fa-check-to-slot fa-lg"></i> Resultados</h2>
        <div id="listado_votacion">
            <i class="fa-solid fa-sync fa-spin"></i>
        </div>
      </div>

    </div>

    <script src="libs/jquery/jquery-3.6.4.min.js"></script>
    <script src="libs/font-awesome/font-awesome.js"></script>
    <script src="libs/sweetalert2/sweetalert2.min.js"></script>
    <script src="libs/alertify-js/alertify.min.js"></script>
    <script src="js/funciones.js"></script>

    <script type="text/javascript">
        $(document).ready(function(){
            cargarFormatoRut();
            obtenerListadoRegiones();
            obtenerListadoCandidatos();
            cargarListadoVotacion();
        });
    </script>

  </body>
</html>
