
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
                        <form id="form-smt-data" method="POST">
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
                                    <input type="hidden" name="qrprocess" value="MOUNTER">
                                    <input type="hidden" name="qc_result" value="">
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
                                <!-- <div class="col-lg-6">
                                    <div class="row">
                                        <label for="boardCode">SCAN BOARD NUMBER</label>
                                        <input type="text" name="boardCode" id="boardCode" class="form-control">
                                        <br>
                                        <input type="radio" id="GOOD" name="qc_result" value="GOOD">
                                        <label for="GOOD">GOOD</label><br>
                                        <input type="radio" id="NG" name="qc_result" value="NG">
                                        <label for="NG">NG</label>
                                    </div>
                                </div> -->
                            </div>
                            <div class="row" style="display:none;">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
									<div class="form-group" style="text-align:right;">
                                        <button type="submit" id="btn-save" class="btn btn-primary">SAVE</button>
                                        <button type="button" id="btn-reload" class="btn btn-default">RESET INPUT</button>
										<!-- <a href="<?= BASEURL; ?>/lottraceability/mounter" class="btn btn-danger">RESET INPUT</a> -->
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
                    if(result.last_process === "MOUNTER"){
                        showErrorMessage("QR "+ inputQR +" Already process in Mounter");
                        $('#qrcode').val('');
                        document.getElementById("qrcode").focus();
                    }else{
                        xdata = [];
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
                            if(data){
                                $('#assycode').val(data.header.assy_code);
                                $('#kepilot').val(data.header.kepi_lot);
                                $('#partmodel').val(data.header.model);
                                // document.getElementById("boardCode").focus();
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
                                
                                setTimeout(function() { 
                                    $("#form-smt-data").submit();
                                }, 1000);
                            }else{
                                showErrorMessage('Model Not Found');
                                document.getElementById("partmodel").focus();
                            }
                        });
                    }
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
                url:base_url+'/lottraceability/saveprocess',
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
                    document.getElementById("qrcode").focus();
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
                    window.location.href = base_url+'/lottraceability/mounter';
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