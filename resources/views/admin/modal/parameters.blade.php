<div class="modal fade" id="param-modal" tabindex="-1" aria-labelledby="param-modal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="aviso-modal">
                    Cambiar Parametros de Sensores
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                @if (count($errors) > 0)
                    @include('secciones.errores')
                @endif

                <form method="POST" action="{{ route('parameters.store') }}" enctype="multipart/form-data">
                    @csrf

                    <div class="form-group">
                        <label for="tmax" class="form-label">Temperatura Maxima</label>
                        <input name="tmax" type="Number" class="form-control" id="tmax" value="{{ $t_maxima->maxim }}" required >
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="hzmax">Vibración Máxima</label>
                        <input name="hzmax" type="Number" class="form-control" id="hzmax" value="{{ $hz_maxima->maxim }}" required>

                    </div>
                    <div class="form-group">
                        <div style="text-align: center">

                            <button type="submit" class="btn btn-primary" style="margin-top: 20px">Guardar</button>
                        </div>
                    </div>

                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
