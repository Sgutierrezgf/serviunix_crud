<div class="container is-fluid mb-6">
    <h1 class="title">Empleados</h1>
    <h2 class="subtitle">Nuevo empleado</h2>
</div>

<div class="container pb-6 pt-6">

    <form class="FormularioAjax" action="<?php echo APP_URL; ?>app/ajax/empleadoAjax.php" method="POST" autocomplete="off" enctype="multipart/form-data">

        <input type="hidden" name="modulo_empleado" value="registrar">
        <div class="columns">
            <div class="column">
                <div class="control">
                    <label>Nickname</label>
                    <input class="input" type="text" name="nickname" pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{3,40}" maxlength="40" required>
                </div>
            </div>
        </div>

        <div class="columns">
            <div class="column">
                <div class="control">
                    <label>Nombres</label>
                    <input class="input" type="text" name="nombre" pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{3,40}" maxlength="40" required>
                </div>
            </div>
            <div class="column">
                <div class="control">
                    <label>Apellidos</label>
                    <input class="input" type="text" name="apellido" pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{3,40}" maxlength="40" required>
                </div>
            </div>
        </div>
        <div class="columns">
            <div class="column">
                <div class="control">
                    <label>Salario</label>
                    <input class="input" type="number" name="salario" pattern="[a-zA-Z0-9]{4,20}" maxlength="20" required>
                </div>
            </div>
            <div class="column">
                <div class="control">
                    <label>Comentarios</label>
                    <input class="input" type="text" name="comentarios" maxlength="70">
                </div>
            </div>
        </div>
        <div class="columns">
            <div class="column">
                <div class="control">
                    <label>Genero</label>
                    <input class="input" type="text" name="genero" pattern="[a-zA-Z0-9]{4,20}" maxlength="20" required>
                </div>
            </div>
            <div class="column">
                <div class="control">
                    <label>Departamento</label>
                    <input class="input" type="text" name="departamento" maxlength="70">
                </div>
            </div>
        </div>
        <div class="columns">
            <div class="column">
                <div class="control">
                    <label>Clave</label>
                    <input class="input" type="password" name="usuario_clave_1" pattern="[a-zA-Z0-9$@.-]{7,100}" maxlength="100" required>
                </div>
            </div>
            <div class="column">
                <div class="control">
                    <label>Repetir clave</label>
                    <input class="input" type="password" name="usuario_clave_2" pattern="[a-zA-Z0-9$@.-]{7,100}" maxlength="100" required>
                </div>
            </div>
        </div>
        <div class="columns">
            <div class="column">
                <div class="file has-name is-boxed">
                    <label class="file-label">
                        <input class="file-input" type="file" name="empleado_foto" accept=".jpg, .png, .jpeg">
                        <span class="file-cta">
                            <span class="file-label">
                                Seleccione una foto
                            </span>
                        </span>
                        <span class="file-name">JPG, JPEG, PNG. (MAX 5MB)</span>
                    </label>
                </div>
            </div>
        </div>
        <p class="has-text-centered">
            <button type="reset" class="button is-link is-light is-rounded">Limpiar</button>
            <button type="submit" class="button is-info is-rounded">Guardar</button>
        </p>
    </form>
</div>