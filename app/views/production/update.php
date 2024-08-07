<section class="content">
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
                    <form action="<?= BASEURL; ?>/production/saveupdate" method="POST">
                        <div class="row clearfix">
                            <div class="col-sm-2">
                                <div class="form-line">
                                    <label for="">PLAN DATE</label>
                                    <input type="date" name="plandate" id="plandate" class="form-control" value="<?= date('Y-m-d'); ?>">
                                </div>
                            </div>
                            <div class="col-sm-2">
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
                                <div class="form-line">
                                    <label for="section">Section</label>
                                    <select name="section" id="section" class="form-control" data-live-search="true" required>
                                        <option value="SMT">SMT</option>
                                        <option value="HANDWORK">HANDWORK</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-1">
                                <div class="form-line">
                                    <br>
                                    <button type="button" class="btn btn-success btn-display-planning">
                                        <i class="material-icons">search</i> Display Data
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="row clearfix">
                            <div class="col-sm-12">
                                <table class="table">
                                    <thead>
                                        <th>NO.</th>
                                        <th style="width:200px;">MODEL</th>
                                        <th style="width:300px;">LOT NUMBER</th>
                                        <th>PLAN QTY</th>
                                        <th></th>
                                    </thead>
                                    <tbody class="mainbody" id="tbl-plan-item">

                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="2"></td>
                                            <td colspan="3" style="text-align:right;">
                                                <button type="button" class="btn btn-success btnAddItem">
                                                    <i class="material-icons">add</i> ADD
                                                </button>
                                                <button type="submit" class="btn btn-primary">
                                                    <i class="material-icons">save</i> SAVE
                                                </button>
                                            </td>
                                        </tr>
                                    </tfoot>
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
        $(function(){
            var count = 0;
            var deletePlanning = 'N';

            $('.btnAddItem').on('click', function(){
                count = count + 1; 

                $('#tbl-plan-item').append(`
                    <tr>
                        <td class="nurut"> </td>
                        <td>
                            <select name="model[]" id="find-model`+count+`" class="find-model" style="width: 200px;"></select>
                        </td>
                        <td>
                            <input type="text" name="lotnumber[]" class="form-control" style="width: 300px;"> 
                        </td>
                        <td>
                            <input type="text" name="inputqty[]" class="form-control" > 
                        </td>
                        <td>
                            <button type="button" class="btn btn-danger btn-sm removeItem hideComponent" counter="`+count+`" id="btnDelete`+count+`">Remove</button>
                        </td>
                    </tr>
                `);

                renumberRows();

                $(document).on('select2:open', (event) => {

                    const searchField = document.querySelector(
                        `.select2-search__field`,
                    );
                    if (searchField) {
                        searchField.focus();
                    }
                });

                $('#find-model'+count).select2({ 
                    placeholder: 'Type Model Name',
                    width: '100%',
                    minimumInputLength: 3,
                    ajax: {
                        url: base_url + '/production/searchMaterial',
                        dataType: 'json',
                        delay: 250,
                        data: function(data){
                            return{
                                searchName: data.term
                            }
                        },
                        processResults: function (data) {
                            return {
                                results: $.map(data.data, function (item) {
                                    return {
                                        text: item.matdesc,
                                        slug: item.matdesc,
                                        id: item.material,
                                        ...item
                                    }
                                })
                            };
                        },
                    }
                });

                $('#btnDelete'+count).on('click', function(e){
                    e.preventDefault();
                    var row_index = $(this).closest("tr").index(); 
                    $(this).closest("tr").remove();
                    renumberRows();
                });
            });

            $('.btn-display-planning').on('click', function(){
                getDailyPlanning();
            });
            
            
            function getDailyPlanning(){
                var planDate = $('#plandate').val();
                var prodLine = $('#prodline').val();
                var shift    = $('#shift').val();
                var section  = $('#section').val();
                // alert(section);
                $.ajax({
                    url: base_url+'/production/getdailyplanning/'+planDate+'/6/1/SMT',
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
                    $('#tbl-plan-item').html('');
                    for(var i = 0; i < data.length; i++){
                        count = count + 1; 

                        $('#tbl-plan-item').append(`
                            <tr>
                                <td class="nurut"> </td>
                                <td>
                                    <select name="model[]" id="find-model`+count+`" class="find-model" style="width: 200px;">
                                        <option value="`+ data[i].partnumber +`">`+ data[i].model +`</option>
                                    </select>
                                </td>
                                <td>
                                    <input type="text" name="lotnumber[]" class="form-control" style="width: 300px;" value="`+ data[i].lot_number +`"> 
                                </td>
                                <td>
                                    <input type="text" name="inputqty[]" class="form-control" value="`+ data[i].plan_qty +`"> 
                                </td>
                                <td>
                                    <button type="button" class="btn btn-danger btn-sm removeItem hideComponent" id="btnDelete`+count+`" counter="`+count+`" data-lotnumber="`+ data[i].lot_number +`" data-model="`+ data[i].model +`" data-partnumber="`+ data[i].partnumber +`">Remove</button>
                                </td>
                            </tr>
                        `);

                        renumberRows();

                        $(document).on('select2:open', (event) => {

                            const searchField = document.querySelector(
                                `.select2-search__field`,
                            );
                            if (searchField) {
                                searchField.focus();
                            }
                        });

                        $('#find-model'+count).select2({ 
                            placeholder: 'Type Model Name',
                            width: '100%',
                            minimumInputLength: 3,
                            ajax: {
                                url: base_url + '/production/searchMaterial',
                                dataType: 'json',
                                delay: 250,
                                data: function(data){
                                    return{
                                        searchName: data.term
                                    }
                                },
                                processResults: function (data) {
                                    return {
                                        results: $.map(data.data, function (item) {
                                            return {
                                                text: item.matdesc,
                                                slug: item.matdesc,
                                                id: item.material,
                                                ...item
                                            }
                                        })
                                    };
                                },
                            }
                        });

                        $('#btnDelete'+count).on('click', function(e){
                            e.preventDefault();
                            var row_index  = $(this).closest("tr").index(); 
                            var currentRow = $(this).closest("tr"); 

                            var _data = $(this).data();
                            console.log(_data);
                            deletePlanning = 'N';

                            // deletePlanning(planDate, _data.lotnumber, _data.model, _data.partnumber, prodLine, shift);
                            $.ajax({
                                url: base_url+'/production/deleteplanning',
                                type: 'POST',
                                dataType: 'json',
                                data:{
                                    'plandate': planDate,
                                    'lotnumber': _data.lotnumber,
                                    'model' : _data.model,
                                    'partnumber': _data.partnumber,
                                    'prodline': prodLine,
                                    'shift': shift
                                },
                                cache:false,
                                success: function(result){

                                },
                                error: function(err){
                                    console.log(err)
                                }
                            }).done(function(data){
                                console.log(data);
                                if(data.msgtype === '1'){                                    
                                    // $(this).closest("tr").remove();
                                    currentRow.remove();
                                    renumberRows();
                                }else{
                                    showErrorMessage(data.message)
                                }
                            });

                            
                        });
                    }
                })
            }

            function renumberRows() {
                $(".mainbody > tr").each(function(i, v) {
                    $(this).find(".nurut").text(i + 1);
                });
            }

            function deletePlanning(pPlandate, pLotnum, pModel, pPartnum, pProdline, pShift){
                $.ajax({
                    url: base_url+'/production/deleteplanning',
                    type: 'POST',
                    dataType: 'json',
                    data:{
                        'plandate': pPlandate,
                        'lotnumber': pLotnum,
                        'model' : pModel,
                        'partnumber': pPartnum,
                        'prodline': pProdline,
                        'shift': pShift
                    },
                    cache:false,
                    success: function(result){

                    },
                    error: function(err){
                        console.log(err)
                    }
                }).done(function(data){
                    console.log(data);
                    if(data.msgtype === '1'){
                        deletePlanning = 'Y';
                    }else{
                        showErrorMessage(data.message)
                    }
                });
            }

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
        });
    </script>