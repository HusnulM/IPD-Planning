<style>
    @media (min-width: 768px) {
        .modal-xl {
            width: 90%;
            max-width:1200px;
        }
    }
</style>
<section class="content">
    <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <h2>
                            <?= $data['menu']; ?>
                        </h2>
                    </div>
                    <div class="body">
                        <form action="<?= BASEURL; ?>/production/save" method="POST">
                            <div class="row clearfix">
                                <div class="col-sm-3">
                                    <div class="form-line">
                                        <label for="">PLAN DATE</label>
                                        <input type="date" name="plandate" id="plandate" class="form-control" value="<?= date('Y-m-d'); ?>">
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-line">
                                        <label for="prodline">Production Line</label>
                                        <select name="prodline" id="prodline" class="form-control" data-live-search="true" required>
                                            <?php foreach($data['lines'] as $d) : ?>
                                                <option value="<?= $d['id']; ?>"><?= $d['description']; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-line">
                                        <label for="shift">Shift</label>
                                        <select name="shift" id="shift" class="form-control" data-live-search="true" required>
                                            <option value="1">Day Shift</option>
                                            <option value="2">Night Shift</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-line" style="text-align:center;">
                                        <br>
                                        <button type="button" class="btn bg-blue" id="btn-show-data">
                                            <i class="material-icons">search</i>SHOW DATA
                                        </button>
                                        <button type="button" class="btn bg-blue" id="btn-monitoring">
                                            <i class="material-icons">search</i> MONITORING VIEW
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="row clearfix">
                                <div class="col-sm-12">
                                    <table class="table">
                                        <thead>
                                            <th>NO.</th>
                                            <th>Time</th>
                                            <th style="width:300px;">Model</th>
                                            <th style="text-align:right;">Target Output</th>
                                            <th style="text-align:right;">Actual Output</th>
                                            <th style="text-align:right;">Variance</th>
                                        </thead>
                                        <tbody class="mainbody" id="tbl-plan-item">

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

            
    </section>
    
    <script src="<?= BASEURL; ?>/plugins/sweetalert/sweetalert.min.js"></script>
    <script>
        function onlyNumberKey(evt) {
            var ASCIICode = (evt.which) ? evt.which : evt.keyCode
            if (ASCIICode > 31 && (ASCIICode < 48 || ASCIICode > 57))
                return false;
            return true;
        }

        $(function(){
            var count = 0;
            $('#btn-monitoring').on('click', function(){
                var _plandate = $('#plandate').val();
                var _prodline = $('#prodline').val();
                var _shift    = $('#shift').val();
                window.open(
                        // base_url+"/production/hourlymonitoringview/data?plandate="+_plandate+"&prodline="+_prodline+"&shift="+_shift,
                        // '_blank' // <- This is what makes it open in a new window.
                        base_url+"/production/hourlymonitoringview/data?viewall=Y",
                        '_blank' // <- This is what makes it open in a new window.
                    );
            });

            $('#btn-show-data').on('click', function(){
                getPlanning();
            });

            function getPlanning(){
                $.ajax({
                    url: base_url+'/production/gethourlyoutput',
                    type: 'POST',
                    dataType: 'json',
                    data:{
                        plandate : $('#plandate').val(),
                        prodline : $('#prodline').val(),
                        shift: $('#shift').val()
                    },
                    cache:false,
                    success: function(result){

                    },
                    error: function(err){
                        console.log(err)
                    }
                }).done(function(data){
                    console.log(data)
                    $('#tbl-plan-item').html('');
                    if(data.length > 0){
                        count = 0;
                        for(var i = 0; i < data.length; i++){
                            count = count + 1;
                            $('#tbl-plan-item').append(`
                                <tr>
                                    <td>`+ count +`</td>
                                    <td>`+ data[i].hourly_time +`</td>
                                    <td>`+ data[i].model +`</td>
                                    <td style="text-align:right;">`+ data[i].target_qty +`</td>
                                    <td style="text-align:right;">`+ data[i].output_qty +`</td>
                                    <td style="text-align:right;">`+ data[i].variance_qty +`</td>
                                </tr>
                            `);
                        }

                        $('.btnInputActual').on('click', function(){
                            var _data = $(this).data();
                            console.log(_data);
                            $('#model_selected').val(_data.model);
                            $('#displ_plan_qty').val(_data.planqty);
                            $('#displ_lot_num').val(_data.lotnumber);
                            getActualQuantity(_data.model);
                            $('#actualQtyModal').modal('show');
                            // document.getElementById("output_qty").focus();
                            setTimeout(function() { 
                                $('#output_qty').focus();
                            }, 1000);
                        });
                    }
                });
            }

            function getActualQuantity(_model){
                // alert(_model)
                $('#tbl-actual-item').html('');
                $.ajax({
                    url: base_url+'/production/getactualdata',
                    type: 'POST',
                    dataType: 'json',
                    data:{
                        plandate : $('#plandate').val(),
                        prodline : $('#prodline').val(),
                        shift: $('#shift').val(),
                        model: _model
                    },
                    cache:false,
                    success: function(result){

                    },
                    error: function(err){
                        console.log(err)
                    }
                }).done(function(data){
                    count = 0;
                    var _shift = '';
                    for(var i = 0; i < data.length; i++){
                        if(data[i].shift == 1){
                            _shift = 'Day Shift';
                        }else if(data[i].shift == 2){
                            _shift = 'Night Shift';
                        }
                        count = count + 1;
                        $('#tbl-actual-item').append(`
                            <tr>
                                <td>`+ count +`</td>
                                <td>`+ data[i].plandate +`</td>
                                <td>`+ data[i].linename +`</td>
                                <td>`+ _shift +`</td>
                                <td>`+ data[i].model +`</td>
                                <td>`+ data[i].lot_number +`</td>
                                <td style="text-align:right;">`+ data[i].output_qty +`</td>
                                <td>`+ data[i].hourly_time +`</td>
                                <td>`+ data[i].createdon +`</td>
                            </tr>
                        `);
                        }
                });
            }

            $('#btn-save-actual').on('click', function(){
                if($('#output_qty').val() === "" ){
                    alert("Input Actual Quantity")
                }else{
                    saveActualQuantity($('#model_selected').val(),$('#output_qty').val(),$('#displ_lot_num').val());
                }
                setTimeout(function() { 
                    $('#output_qty').focus();
                }, 1000);
            });

            function saveActualQuantity(_model, _quantity, _lotnumber){
                $.ajax({
                    url: base_url+'/production/saveactualdata',
                    type: 'POST',
                    dataType: 'json',
                    data:{
                        plandate : $('#plandate').val(),
                        prodline : $('#prodline').val(),
                        shift: $('#shift').val(),
                        model: _model,
                        lot_number: _lotnumber,
                        quantity: _quantity,
                        hourlytime: $('#hourlytime').val()
                    },
                    cache:false,
                    success: function(result){

                    },
                    error: function(err){
                        console.log(err)
                    }
                }).done(function(data){
                    console.log(data)
                    if(data.msgtype === "1"){
                        showSuccessMessage(data.message);
                        $('#actualQtyModal').modal('hide');
                    }else{
                        showErrorMessage(data.message);
                    }
                    getPlanning();
                    $('#output_qty').val('')
                })
            }

            function renumberRows() {
                $(".mainbody > tr").each(function(i, v) {
                    $(this).find(".nurut").text(i + 1);
                });
            }

            function showSuccessMessage(message) {
                swal({title: "Success!", text: message, type: "success"},
                    function(){ 
                        // window.location.href = base_url+'/wos';
                        // document.getElementById("lotnumber").focus();
                    }
                );
            }

            function showErrorMessage(message){
                swal({title:"", text: message, type:"warning"},
                    function(){
                        // document.getElementById("lotnumber").focus();
                    }
                );
            }
        });
    </script>