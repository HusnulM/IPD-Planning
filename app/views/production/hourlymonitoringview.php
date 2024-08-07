<!-- <META HTTP-EQUIV="Refresh" Content="40; <?= BASEURL; ?>/production/hourlymonitoringview/data?plandate=<?= $data['plandate']; ?>&prodline=<?= $data['prodline']; ?>&shift=<?= $data['shift']; ?>"> -->
<META HTTP-EQUIV="Refresh" Content="20; <?= BASEURL; ?>/production/hourlymonitoringview/data?viewall=Y">
<div class="content" style="margin-top:60px;">
    <div class="container-fluid">   
        <div id="msg-alert">
            <?php
                Flasher::msgInfo();
            ?>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="header">
                        <h3><?= $data['menu']; ?></h3>
                    </div>
                    <div class="body">
                        <div class="row">
                            <?php foreach($data['lines'] as $line): ?>
                                <div class="col-lg-3">
                                    <table>
                                        <tr>
                                            <td>PLAN DATE</td>
                                            <td style="width:100px;"></td>
                                            <td><?= $data['plandate']; ?></td>
                                        </tr>
                                        <tr>
                                            <td>PRODUCTION LINE</td>
                                            <td style="width:100px;"></td>
                                            <td><?= $line['description']; ?></td>
                                        </tr>
                                        <tr>
                                            <td>SHIFT</td>
                                            <td></td>
                                            <td>
                                            <?php if($data['shift'] == 1): ?>
                                                Day Shift
                                            <?php else: ?>
                                                Night Shift
                                            <?php endif; ?>
                                            </td>
                                        </tr>
                                    </table>
                                    <hr>
                                    <table class="table table-bordered table-striped table-hover table-sm">
                                        <thead>
                                            <th>NO.</th>
                                            <th style="width:300px;">Time</th>
                                            <th style="width:300px;">Model</th>
                                            <th style="text-align:right;">Target</th>
                                            <th style="text-align:right;">Actual</th>
                                            <th style="text-align:right;">Variance</th>
                                        </thead>
                                        <tbody class="mainbody" id="tbl-plan-item">
                                            <?php $count = 0; ?>
                                            <?php foreach($data['rdata'] as $row) : ?>
                                                <?php if($row['productionline'] == $line['id']): ?>
                                                <tr>
                                                    <td><?= $count = $count+1; ?></td>
                                                    <td><?= $row['hourly_time']; ?></td>
                                                    <td><?= $row['model']; ?></td>
                                                    <td style="text-align:right;"><?= $row['target_qty']; ?></td>
                                                    <td style="text-align:right;"><?= $row['output_qty']; ?></td>
                                                    <td style="text-align:right;"><?= $row['variance_qty']; ?></td>
                                                </tr>
                                                <?php endif; ?>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>                            
                            <?php endforeach; ?>                    
                        </div>                        
                    </div>
                </div>
            </div>
        </div>        
    </div>     
</div>


    <script>
        $(document).ready(function() {
            // $('#report-data').DataTable( {
            //     // "scrollY": 200,
            //     // "scrollX": true,
            //     // "pageResize": true
            // } );

            $('#leftsidebar').hide();
        } );
    </script>