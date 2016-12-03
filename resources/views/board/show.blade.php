@extends('layouts.default')

@section('content')
    <h1>Battileship Laravel</h1>
    <div id="battleship">

        <div class="row">

            <div class="col-sm-12 sysmsg">@{{ message }}</div>

            <div class="alert" v-bind:class="[alertType]" v-if="!!alert" transition="fade">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                @{{ alert }}
                <ul v-if="!!errors">
                    <li v-for="error in errors">@{{ error }}</li>
                </ul>
            </div>

            <div class="col-md-6">
                <h3>My map</h3>
                <div class="map mine">
                    <table>
                        <tbody>
                        @foreach($board->table as $row => $cols)
                            <tr>
                                @foreach($cols as $col => $result)
                                    <td class="row{{$row}} col{{$col}}" @click="addShipToBoard({{$row}}, {{$col}})"></td>
                                @endforeach
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>

                <div id="ships">
                    Orientation:
                    <select v-model="orientation">
                        <option value="h" selected>Horizontal</option>
                        <option value="v">Vertical</option>
                    </select>
                    <div v-for="availableShip in availableShips">
                        @{{ availableShip.name }}
                        <a href="#" @click.prevent="setShipToPosition(availableShip)">Position it</a>
                    </div>
                </div>

            </div>
            <div class="col-md-6">
                <h3>Opponent's map</h3>
                <div class="map opponent">
                    <table>
                        <tbody>
                        @foreach($board->table as $row => $cols)
                            <tr>
                                @foreach($cols as $col => $result)
                                    <td class="row{{$row}} col{{$col}}" @click="makeAShot({{$row}}, {{$col}})"></td>
                                @endforeach
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
    <div class="copyright">
        THIS GAME IS OPEN-SOURCE (MIT) AND CREATED BY <a href="http://ivanlolivier.com">IVAN L'OLIVIER</a>
    </div>

    <script>
        Vue.http.headers.common['X-CSRF-TOKEN'] = '{{ csrf_token() }}';

        new Vue({
            el: "#battleship",

            data: {
                availableShips: <?=json_encode($board->available_ships->toArray())?>,
                shipToPosition: null,
                orientation: 'v',

                ships:  <?=json_encode($board->ships)?>,

                message: '',

                alert: '',
                alertType: 'alert-success',
                errors: []
            },

            ready: function () {
                this.ships.forEach(function (ship) {
                    var pos = ship.initial_position;

                    $('.map.mine .col' + pos.col + '.row' + pos.row)
                            .addClass('ship')
                            .addClass(ship.ship.name)
                            .addClass('s' + ship.ship.length)
                            .addClass(this.orientation);
                });
            },

            methods: {
                setShipToPosition: function (availableShip) {
                    this.availableShips.splice(this.availableShips.indexOf(availableShip), 1);

                    this.message = 'Positioning ' + availableShip.name;

                    this.shipToPosition = availableShip;
                },

                addShipToBoard(row, col) {
                    if (!this.shipToPosition) return;

                    var params = {
                        'ship_name': this.shipToPosition.name,
                        'orientation': this.orientation,
                        'row': row,
                        'col': col
                    };

                    this.$http
                            .post('{{ route('addShip', ['id' => $user_id]) }}', params)
                            .then(function (result) {
                                this.message = '';

                                $('.map.mine .col' + col + '.row' + row)
                                        .addClass('ship')
                                        .addClass(this.shipToPosition.name)
                                        .addClass('s' + this.shipToPosition.length)
                                        .addClass(this.orientation);

                                this.shipToPosition = null;

                                return true;
                            }.bind(this))
                            .catch(this.handleAjaxError.bind(this));
                },

                makeAShot: function (row, col) {
                    var params = {
                        'row': row,
                        'col': col
                    };

                    this.$http
                            .post('{{ route('shot', ['id' => $user_id]) }}', params)
                            .then(function (response) {
                                this.message = response.message;

                                var imageClass = (response.result == 'miss') ? 'miss' : 'hit';

                                $('.map.opponent .col' + col + '.row' + row)
                                        .addClass('boom')
                                        .addClass(imageClass);
                            }.bind(this));
                },

                handleAjaxError: function (response) {
                    this.alertType = 'alert-danger';
                    this.alert = 'Errors for the action:';
                    for (var key in response.data) {
                        if (!response.data.hasOwnProperty(key)) continue;
                        this.errors.push(response.data[key]);
                    }
                },

                showAlert: function (msg, time, callback) {
                    if (!time) time = 10000;
                    if (!callback) callback = function () {
                        this.alert = ''
                    };
                    this.alert = msg;
                    setTimeout(callback.bind(this), time);
                }

            }
        });
    </script>
@endsection
