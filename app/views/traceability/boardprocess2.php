
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
                        <form id="form-board-process" name="inputForm" method="POST">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xm-12">
                                        <label for="qrcode">Scan Board Number</label>
                                        <input type="text" name="board_code" id="board_code" class="form-control" autocomplete="off" required/>
                                        <input type="hidden" name="qrcode" id="qrcode" class="form-control" autocomplete="off"/>
                                        <input type="hidden" name="board_process" id="board_process" class="form-control" value="FVI"/>
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
                                    <input type="hidden" name="qrprocess" value="SMTAI">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xm-12">
                                        <input type="radio" id="GOOD" name="scan_result" value="GOOD">
                                        <label for="GOOD"><b>GOOD</b></label><br>
                                        <input type="radio" id="NG" name="scan_result" value="NG">
                                        <label for="NG"><b>NG</b></label>
                                    </div>
                                </div>
                                
                            </div>
                            <div class="row">
                                <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
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

        document.getElementById("board_code").focus();

        var rad = document.inputForm.scan_result;
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
                    // $("#form-board-process").submit();
                }
            };
        }

        $('#btn-reload').on('click', function(){
            window.location.reload();
        });

        $('#board_code').keydown(function(e){            
            var inputQR = this.value;
            if(e.keyCode == 13) {
                xdata = [];
                $.ajax({
                    url: base_url+'/lottraceability/findboardprocess/data?boardcode='+inputQR,
                    type: 'GET',
                    dataType: 'json',
                    cache:false,
                    success: function(result){

                    },
                    error: function(err){
                        console.log(err)
                    }
                }).done(function(data){
                    console.log(data);
                    $('#assycode').val(data.board_data.assy_code);
                    $('#kepilot').val(data.board_data.kepi_lot);
                    $('#partmodel').val(data.board_data.model);
                    $('#qrcode').val(data.board_data.qrcode);
                    $('#prevprocess').val(data.board_process.board_process);
                });
            }
        });

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

        $('#form-board-process').on('submit', function(event){
            event.preventDefault();
                
            var formData = new FormData(this);
            console.log($(this).serialize())
            $.ajax({
                url:base_url+'/lottraceability/saveboardprocess',
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
                    document.getElementById("board_code").focus();
                    $('#kepilot').val('');
                    $('#partmodel').val('');
                    $('#assycode').val('');
                    $('#qrcode').val('');
                    $('#board_code').val('');
                    $('#prevprocess').val('');
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