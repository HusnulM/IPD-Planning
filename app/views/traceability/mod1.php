
<section class="content">
    <div class="container-fluid">
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div id="msg-alert" class="msg-alert">
                    <?php
                        Flasher::msgInfo();
                    ?>
                </div>
                <div class="card">
                    <div class="header">
                        <h2>
                            <?= $data['menu']; ?>
                        </h2>
                    </div>
                    <div class="body">
                        <!-- action="<?= BASEURL; ?>/lottraceability/saveqrcode" -->
                        <form id="form-smt-data" name="inputForm" method="POST">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xm-12">
                                        <label for="qrcode">QR CODE</label>
                                        <input type="text" name="qrcode" id="qrcode" class="form-control" autocomplete="off" required/>
                                    </div>
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xm-12">
                                        <label for="partmodel">MODEL</label>
                                        <input type="text" name="partmodel" id="partmodel" class="form-control" readonly/>
                                    </div>
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xm-12">
                                        <label for="kepilot">KEPI LOT NO</label>
                                        <input type="text" name="kepilot" id="kepilot" class="form-control" autocomplete="off" readonly/>
                                    </div>
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xm-12">
                                        <label for="assycode">ASSY CODE</label>
                                        <input type="text" name="assycode" id="assycode" class="form-control" autocomplete="off" readonly/>
                                    </div>
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xm-12">
                                        <label for="assycode">PREVIOUS PROCESS</label>
                                        <input type="text" name="prevprocess" id="prevprocess" class="form-control" autocomplete="off" readonly/>
                                    </div>
                                    <input type="hidden" name="qrprocess" value="MOD1">
                                </div>
                                <div class="col-lg-6">
                                    <div class="row">
                                        <table class="table" width="100%;">
                                            <thead>
                                                <th>No.</th>
                                                <th>Board</th>
                                            </thead>
                                            <tbody id="board-items">

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="row">
                                        <input type="radio" id="GOOD" name="qc_result" value="GOOD">
                                        <label for="GOOD">GOOD</label><br>
                                        <input type="radio" id="NG" name="qc_result" value="NG">
                                        <label for="NG">NG</label>
                                        <br>
                                        <label for="boardCode" class="ngBarcode" style="display:none;">SCAN BOARD NUMBER</label>
                                        <input type="text" name="boardCode" id="boardCode" class="form-control ngBarcode" style="display:none;">
                                    </div>
                                    <!-- <hr> -->
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
									<div class="form-group" style="text-align:right;">
                                        <button type="submit" id="btn-save" class="btn btn-primary">SAVE</button>
										<button type="button" id="btn-reload" class="btn btn-default">RESET INPUT</button>
									</div>
								</div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script src="<?= BASEURL; ?>/plugins/sweetalert/sweetalert.min.js"></script>
<script>
    $(document).ready(function(){
        $(window).keydown(function(event){
            if(event.keyCode == 13) {
                event.preventDefault();
                return false;
            }
        });

        var locations = [];
        var listItems = '';
        var xdata = [];

        document.getElementById("qrcode").focus();

        var rad = document.inputForm.qc_result;
        var prev = null;
        for(var i = 0; i < rad.length; i++) {
            rad[i].onclick = function () {
                (prev)? console.log(prev.value):null;
                if(this !== prev) {
                    prev = this;
                }
                if(this.value === "NG"){
                    $('.ngBarcode').show();
                    document.getElementById("boardCode").focus();
                }else{
                    $('.ngBarcode').hide();
                }
            };
        }

        $('#btn-reload').on('click', function(){
            window.location.reload();
        });

        function setLineItems(){

            $('.dropdown-toggle').hide();
            $(document).on('select2:open', (event) => {
        
                const searchField = document.querySelector(
                    `.select2-search__field`,
                );
                if (searchField) {
                    searchField.focus();
                }
            });           
            if(xdata.length > 0){
                $('#find-line').select2({ 
                    width: '100%',
                    minimumInputLength: 0,
                    data: xdata
                });
            }else{
                $('#find-line').html('');
            }
        }

        $('#qrcode').keydown(function(e){            
            var inputQR = this.value;
            if(e.keyCode == 13) {
                xdata = [];
                $.ajax({
                    url: base_url+'/lottraceability/latestprocess/data?qrcode='+inputQR,
                    type: 'GET',
                    dataType: 'json',
                    cache:false,
                    success: function(result){

                    },
                    error: function(err){
                        console.log(err)
                    }
                }).done(function(result){
                    if(result.last_process === "MOD1"){
                        showErrorMessage("QR "+ inputQR +" Already process in MOD1");
                        $('#qrcode').val('');
                        document.getElementById("qrcode").focus();
                    }else{
                        $.ajax({
                            url: base_url+'/lottraceability/getqrdetails/data?qrcode='+inputQR,
                            type: 'GET',
                            dataType: 'json',
                            cache:false,
                            success: function(result){
        
                            },
                            error: function(err){
                                console.log(err)
                            }
                        }).done(function(data){
                            console.log(data)
                            // alert(data.header.model)
                            if(data.header){
                                $('#assycode').val(data.header.assy_code);
                                $('#kepilot').val(data.header.kepi_lot);
                                $('#partmodel').val(data.header.model);
                                $('#prevprocess').val(data.header.last_process);
                                document.getElementById("boardCode").focus();
                                $('#board-items').html('');
                                var boardQty = 0;
                                var boardID = 0;
                                var nextboardID = 1;
                                for(var i = 0; i < data.detail.length; i++){
                                    boardID += 1;
                                    nextboardID += 1;
                                    $('#board-items').append(
                                      `<tr>
                                        <td>`+boardID+`</td>
                                        <td>
                                            <input type="text" name="boards[]" id="board`+boardID+`" value="`+data.detail[i].board_code+`" class="form-control" required>
                                        </td>
                                      </tr>`  
                                    );
                                }
        
                            }else{
                                showErrorMessage('QR Not Found');
                                $('#qrcode').val('');
                                document.getElementById("qrcode").focus();
                            }
                        });
                    }
                });
            }
        });

        $('#barcode').keydown(function(e){
            if(e.keyCode == 13) {
                var inputBarcode = this.value;
                $.ajax({
                    url: base_url+'/barcodeserial/barcodeDetail/data?barcode='+inputBarcode,
                    type: 'GET',
                    dataType: 'json',
                    cache:false,
                    success: function(result){

                    },
                    error: function(err){
                        console.log(err)
                    }
                }).done(function(data){
                    console.log(data)
                    if(data['barcode']){
                        $('#partnumber').val(data['barcode'].part_number);
                        $('#lotnumber').val(data['barcode'].part_lot);
                        $('#partlocation').val(data['location'][0].assy_location);
                        document.getElementById("smtline").focus();
                    }else{
                        showErrorMessage('Barcode Serial '+ inputBarcode +'  Not Found');
                        $('#barcode').val('');
                    }

                    // setLineItems();
                });
                
            }
        });

        $('#smtline').keydown(function(e){
            if(e.keyCode == 13) {
                document.getElementById("smtshift").focus();
            }
        });

        $('#kepilot').keydown(function(e){
            if(e.keyCode == 13) {
                var inputKepi = this.value;
                if(inputKepi === ''){
                    showErrorMessage('Input / Scan KEPI Lot');
                }else{
                    document.getElementById("partmodel").focus();
                }                
            }
        });

        // $('#partmodel').keydown(function(e){
        //     if(e.keyCode == 13) {
        //         document.getElementById("assycode").focus();
        //     }
        // });

        function showSuccessMessage(message) {
            swal({title: "Success!", text: message, type: "success"},
                function(){ 
                    // window.location.href = base_url+'/wos';
                }
            );
        }

        function showErrorMessage(message){
            swal("", message, "warning");
        }

        $('#form-smt-data').on('submit', function(event){
            event.preventDefault();
                
            var formData = new FormData(this);
            console.log($(this).serialize())
            $.ajax({
                url:base_url+'/lottraceability/saveqrcode',
                method:'post',
                data:formData,
                dataType:'JSON',
                contentType: false,
                cache: false,
                processData: false,
                beforeSend:function(){
                    $('#btn-save').attr('disabled','disabled');
                },
                success:function(data)
                {
                    console.log(data);
                },
                error:function(err){
                    // showErrorMessage(JSON.stringify(err))
                }
            }).done(function(result){
                console.log(result);
                if(result.msgtype === "1"){
                    document.getElementById("kepilot").focus();
                    $('#kepilot').val('');
                    $('#partmodel').val('');
                    $('#assycode').val('');
                    $('#qrcode').val('');
                    $('#board-items').html('');
                    showSuccessMessage(result.message);
                }else{
                    showErrorMessage(JSON.stringify(result.message))  
                }

                $("#btn-save").attr("disabled", false);
            });
        });

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