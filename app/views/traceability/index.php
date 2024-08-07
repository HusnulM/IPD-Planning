
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
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xm-12">
                                            <label for="kepilot">KEPI LOT NO</label>
                                            <input type="text" name="kepilot" id="kepilot" class="form-control" autocomplete="off" required/>
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xm-12">
                                            <label for="partmodel">MODEL</label>
                                            <input type="text" name="partmodel" id="partmodel" class="form-control" />
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xm-12">
                                            <label for="assycode">ASSY CODE</label>
                                            <input type="text" name="assycode" id="assycode" class="form-control" autocomplete="off" readonly/>
                                        </div>
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xm-12">
                                            <label for="qrcode">QR CODE</label>
                                            <input type="text" name="qrcode" id="qrcode" class="form-control" autocomplete="off" required/>
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-lg-6 col-md-12 col-sm-12 col-xm-12">
                                            <label for="smtline">LINE</label>
                                            <select name="smtline" id="smtline" class="form-control" required>
                                                <option value="">-- Select Line --</option>
                                                <?php foreach($data['lines'] as $d) : ?>
                                                    <option value="<?= $d['id']; ?>"><?= $d['description']; ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <div class="col-lg-6 col-md-12 col-sm-12 col-xm-12">
                                            <label for="smtshift">OPERATOR</label>
                                            <input type="text" name="smtshift" id="smtshift" class="form-control" value="<?= $_SESSION['usr']['name']; ?>" autocomplete="off" readonly="true"/>
                                        </div>
                                    </div>
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
                            </div>
                            <div class="row">
                                <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
									<div class="form-group">
                                        <button type="submit" id="btn-save" class="btn btn-primary">SAVE</button>
										<a href="<?= BASEURL; ?>/smtprocess" class="btn btn-danger">CANCEL</a>
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

        document.getElementById("kepilot").focus();

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

        $('#partmodel').keydown(function(e){            
            var inputMaterial = this.value;
            if(e.keyCode == 13) {
                xdata = [];
                $.ajax({
                    url: base_url+'/material/getMaterialbyModel/data?matdesc='+inputMaterial,
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
                    if(data){
                        $('#assycode').val(data.material);
                        $('#uom').val(data.matunit);
                        document.getElementById("qrcode").focus();
                        
                        $('#board-items').html('');
                        var boardQty = 0;
                        var boardID = 0;
                        var nextboardID = 1;
                        if(data.board_qty > 0){
                            for(var i = 0; i<data.board_qty; i++){
                                boardID += 1;
                                nextboardID += 1;
                                $('#board-items').append(
                                  `<tr>
                                    <td>`+boardID+`</td>
                                    <td>
                                        <input type="text" name="boards[]" id="board`+boardID+`" class="form-control" required>
                                    </td>
                                  </tr>`  
                                );

                                // $('#board'+boardID).keydown(function(e){
                                //     var inputVal = this.value;
                                //     if(e.keyCode == 13) {
                                //         document.getElementById("board"+nextboardID).focus();
                                //     }
                                // });
                            }
                        }


                    }else{
                        showErrorMessage('Model Not Found');
                        document.getElementById("partmodel").focus();
                    }

                    $('#board1').keydown(function(e){
                        var inputVal = this.value;
                        if(e.keyCode == 13) {
                            document.getElementById("board2").focus();
                        }
                    });

                    $('#board2').keydown(function(e){
                        var inputVal = this.value;
                        if(e.keyCode == 13) {
                            document.getElementById("board3").focus();
                        }
                    });

                    $('#board3').keydown(function(e){
                        var inputVal = this.value;
                        if(e.keyCode == 13) {
                            document.getElementById("board4").focus();
                        }
                    });

                    $('#board4').keydown(function(e){
                        var inputVal = this.value;
                        if(e.keyCode == 13) {
                            document.getElementById("board5").focus();
                        }
                    });

                    $('#board5').keydown(function(e){
                        var inputVal = this.value;
                        if(e.keyCode == 13) {
                            document.getElementById("board6").focus();
                        }
                    });

                    $('#board6').keydown(function(e){
                        var inputVal = this.value;
                        if(e.keyCode == 13) {
                            document.getElementById("board7").focus();
                        }
                    });

                    $('#board7').keydown(function(e){
                        var inputVal = this.value;
                        if(e.keyCode == 13) {
                            document.getElementById("board8").focus();
                        }
                    });

                    $('#board8').keydown(function(e){
                        var inputVal = this.value;
                        if(e.keyCode == 13) {
                            document.getElementById("board9").focus();
                        }
                    });

                    $('#board9').keydown(function(e){
                        var inputVal = this.value;
                        if(e.keyCode == 13) {
                            document.getElementById("board10").focus();
                        }
                    });
                });
            }
        });

        // $('#assycode').keydown(function(e){            
        //     var inputMaterial = this.value;
        //     if(e.keyCode == 13) {
        //         xdata = [];
        //         $.ajax({
        //             url: base_url+'/material/getMaterialbyCode/data?material='+inputMaterial,
        //             type: 'GET',
        //             dataType: 'json',
        //             cache:false,
        //             success: function(result){

        //             },
        //             error: function(err){
        //                 console.log(err)
        //             }
        //         }).done(function(data){
        //             // console.log(data)
        //             if(data){
        //                 $('#partmodel').val(data.matdesc);
        //                 $('#uom').val(data.matunit);
        //                 document.getElementById("kepilot").focus();
                        
        //             }else{
        //                 showErrorMessage('Assy Code Not Found');
        //             }
        //         });
        //     }
        // });

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