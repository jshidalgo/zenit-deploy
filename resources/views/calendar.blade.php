@extends('home')
@section('content')
<section id="view-calendar">
    <div class="text-intro">
        <h1>Visor y gestor de eventos</h1>
        <span>Agrega, actualiza o elimina eventos</span>
    </div>
    <div class="container py-5">
        <?php
        date_default_timezone_set('America/Bogota');
        //mktime($hora,$min,$seg,$mes,$dia,$anio)
        //t numero de dias del mes date('t', $first_day);
        $numDiasAnterior = date('t', mktime(0, 0, 0, (date('m') - 1), "01", "2020"));
        $numDias = date('t');
        $hoy = date('d');
        $first_day = date('N', mktime(0, 0, 0, date('m'), "01", "2020")); //Calcula el primer día del mes que sería el 02
        // var_dump($first_day);
        ?>
        <!-- Calendar -->
        <div class="calendar shadow bg-white p-5">
            <div class="change-calendar">
                <i class="fas fa-angle-left fa-2x"></i>
                <i class="fas fa-angle-right fa-2x"></i>
            </div>
            <div class="d-flex align-items-center"><i class="fa fa-calendar fa-3x mr-3"></i>
                <h2 class="month font-weight-bold mb-0 text-uppercase">{{date('d F Y')}}</h2>
            </div>
            <p class="font-italic text-muted mb-5">No hay eventos para este día.</p>
            <ol class="day-names list-unstyled">
                <li class="font-weight-bold text-uppercase">Lun</li>
                <li class="font-weight-bold text-uppercase">Mar</li>
                <li class="font-weight-bold text-uppercase">Mie</li>
                <li class="font-weight-bold text-uppercase">Juv</li>
                <li class="font-weight-bold text-uppercase">Vie</li>
                <li class="font-weight-bold text-uppercase">Sab</li>
                <li class="font-weight-bold text-uppercase">Dom</li>
            </ol>

            <ol class="days list-unstyled">
                @for ($i = (($numDiasAnterior-$first_day)+2) ; $i <= $numDiasAnterior; $i++) <li class="outside">
                    <div class="date">{{$i}}</div>
                    </li>
                    @endfor
                    @for ($i = 1; $i <= (36-$first_day); $i++) @if ($i==$hoy) <li>
                        <div class="date today">{{$i}}</div>
                        </li>
                        @elseif ($i <= $numDias) <li>
                            <div class="date">{{$i}}</div>
                            </li>
                            @else
                            <li class="outside">
                                <div class="date">{{$i - $numDias}}</div>
                            </li>
                            @endif
                            @endfor
            </ol>
            <div class="contenedor">
                <div class="botonF1 row justify-content-center align-items-center">
                    <i class="fas fa-plus"></i>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
@section('scripts')
<script>
    //actualizar a fechas actuales
</script>
@endsection

<!-- Tipos de eventos
    ----Colores eventos
    <div class="event bg-warning">event name</div> - naranja
    <div class="event bg-success">event name</div> - verde 
    <div class="event bg-primary">event name</div> - azul
    <div class="event bg-info">event name</div> - azul claro
   ----- Evento normal, de un solo dia
    <div class="date">1</div>
    <div class="event bg-success">Event with Long Name</div>

    --- multiple evento para el 21
    <div class="date">21</div>
    <div class="event bg-primary">Event Name</div>
    <div class="event bg-success">Event Name</div>

    -- evnto que se prolonga por mas de un dia, esta caso hasta 2, se puede llegar a 7
    <div class="date">13</div>
    <div class="event all-day begin span-2 bg-warning">Event Name</div> - uso de begin en dado caso que haya cambio de semana

    <div class="date">15</div>
    <div class="event all-day end bg-success">Event Name</div> - uso de end para recibir a comienzo de semana
 -->