
<section class="content">
    <div class="container-fluid">
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div id="msg-alert" class="msg-alert">
                    <?php
                        Flasher::msgInfo();
                    ?>
                </div>
                <div id="msg-box" style="display:none;">
                    <div class="alert alert-success alert-dismissible" role="alert" id="msg-box-success" style="display:none;">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <strong>QA Process Created</strong>
                    </div>
                    <div class="alert alert-danger alert-dismissible" role="alert" id="msg-box-error" style="display:none;">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <strong>QA Process Failed</strong>
                    </div>
                </div>
                <div class="card">
                    <div class="header">
                        <h2>
                            <?= $data['menu']; ?>
                        </h2>
                    </div>
                    <div class="body">
                    <!-- id="form-submit-data"  -->
                        <form action="<?= BASEURL; ?>/qainspection/save" method="POST">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xm-12">
                                            <label for="kepilot">KEPI LOT NO</label>
                                            <input type="text" name="kepilot" id="kepilot" class="form-control" autocomplete="off" required/>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xm-12">
                                            <label for="quantity">QUANTITY</label>
                                            <input type="text" name="quantity" id="quantity" class="form-control" autocomplete="off" required/>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xm-12">
                                            <label for="manpower_name">MANPOWER NAME</label>
                                            <input type="text" name="manpower_name" id="manpower_name" class="form-control" autocomplete="off" required/>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xm-12">
                                            <label for="qa_result">QA RESULT</label>
                                            <select name="qa_result" id="qa_result" class="form-control">
                                                <option value="">Select QA Result</option>
                                                <option value="GOOD">GOOD</option>
                                                <option value="NG">NG</option>
                                                <option value="HOLD">HOLD</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xm-12">
                                            <label for="assycode">ASSY CODE</label>
                                            <input type="text" name="assycode" id="assycode" class="form-control" autocomplete="off" readonly="true"/>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xm-12">
                                            <label for="partmodel">MODEL</label>
                                            <input type="text" name="partmodel" id="partmodel" class="form-control"  readonly="true"/>
                                        </div>
                                        <!-- <div class="col-lg-6 col-md-12 col-sm-12 col-xm-12">
                                            <label for="lotnumber">PART LOT NO</label>
                                            <input type="text" name="lotnumber" id="lotnumber" class="form-control" autocomplete="off" readonly="true"/>
                                        </div> -->
                                    </div>      
                                    <div class="row qa_result" style="display:none;">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xm-12">
                                            <label for="qa_remark">FAILURE REMARK</label>
                                            <input type="text" name="qa_remark" id="qa_remark" class="form-control" autocomplete="off"/>
                                        </div>
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xm-12">
                                            <label for="qa_defect_qty">QA DEFECT QUANTITY</label>
                                            <input type="text" name="qa_defect_qty" id="qa_defect_qty" class="form-control" autocomplete="off"/>
                                        </div>
                                    </div>                      
                                </div>                                
                            </div>
                            <div class="row">
                                <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
									<div class="form-group">
                                        <button type="submit" id="btn-save" class="btn btn-primary">SAVE</button>
										<a href="<?= BASEURL; ?>" class="btn btn-danger">CANCEL</a>
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
        // setLineItems();
        // $('.dropdown-toggle').hide();
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
            // alert(xdata.length)
            if(xdata.length > 0){
                // alert(1)
                $('#find-line').select2({ 
                    width: '100%',
                    minimumInputLength: 0,
                    data: xdata
                });
            }else{
                $('#find-line').html('');
            }
        }

        document.getElementById("kepilot").focus();

        $('#kepilot').keydown(function(e){
            var inputMaterial = this.value;
            if(e.keyCode == 13) {                
                $.ajax({
                    url: base_url+'/ageingprocess/checkKepiLot/data?kepilot='+inputMaterial,
                    type: 'GET',
                    dataType: 'json',
                    cache:false,
                    success: function(result){

                    },
                    error: function(err){
                        console.log(err)
                    }
                }).done(function(data){
                    // console.log(data)
                    if(data){
                        $('#partmodel').val(data.matdesc);
                        // $('#lotnumber').val(data.part_lot);
                        $('#assycode').val(data.assy_code);
                        document.getElementById("quantity").focus();                        
                    }else{
                        showErrorMessage('Kepi Lot '+ inputMaterial +' Not Found');
                        $('#kepilot').val('');
                    }
                });
            }
        });

        $('#quantity').keydown(function(e){
            if(e.keyCode == 13) {
                document.getElementById("manpower_name").focus();
            }
        });

        $('#manpower_name').keydown(function(e){
            if(e.keyCode == 13) {
                document.getElementById("ft_jig_no").focus();
            }
        });

        $('#ft_jig_no').keydown(function(e){
            if(e.keyCode == 13) {
                document.getElementById("ft_result").focus();
            }
        });

        $('#ft_result').keydown(function(e){
            if(e.keyCode == 13) {
                document.getElementById("ft_remark").focus();
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

        $('#qa_result').on('change',function(){
            if(this.value === 'NG'){
                $('.qa_result').show();
                $("#qa_remark").prop('required',true);
                $("#qa_defect_qty").prop('required',true);
            }else{
                $('.qa_result').hide();
                $('#qa_remark').val('');
                $('#qa_defect_qty').val('');
                $("#qa_remark").prop('required',false);
                $("#qa_defect_qty").prop('required',false);
            }
        });

        $('#form-submit-data').on('submit', function(event){
            event.preventDefault();
                
            var formData = new FormData(this);
            // console.log($(this).serialize())
            $.ajax({
                url:base_url+'/qainspection/saveqa',
                method:'post',
                data:formData,
                dataType:'JSON',
                contentType: false,
                cache: false,
                processData: false,
                beforeSend:function(){
                    // showBasicMessage();
                },
                success:function(data)
                {
                    console.log(data);
                },
                error:function(err){
                    // showErrorMessage(JSON.stringify(err))
                    console.log(JSON.stringify(err));
                }
            }).done(function(result){
                console.log(result);
                $('#msg-box').show();
                if(result.msgtype === "1"){
                    // $("#qrcode, #failure_remark, #defect_qty").val('');
                    $('#msg-box-success').show();
                    $('#msg-box-error').hide();
                    setTimeout(function(){ 
                        $('#msg-box').hide();
                    }, 5000);
                }else{
                    $('#msg-box-error').show();
                    $('#msg-box-success').hide();
                    setTimeout(function(){ 
                        $('#msg-box').hide();
                    }, 5000);
                }
            });
        });
    });
</script>