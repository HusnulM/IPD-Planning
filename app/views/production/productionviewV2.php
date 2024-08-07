<META HTTP-EQUIV="Refresh" Content="20; <?= BASEURL; ?>/production/productionview">

<div class="content" style="margin-top:60px;">
        <div class="container-fluid">   
            <div id="msg-alert">
                <?php
                    Flasher::msgInfo();
                ?>
            </div>
            
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="card">
                            <div class="header">
                                <h3>Production Monitoring</h3>
                            </div>
                            <div class="body">
                                <?php 
                                    $dayShift   = '07:00:00';
                                    $nightShift = '19:00:00';
                                ?>
                                
                                <div class="row">                   
                                    <!-- View Day 1 -->
                                    <?php if($data['ctime']['servertime'] >= $dayShift && $data['ctime']['servertime'] <= $nightShift): ?>
                                    <div class="col-lg-6">
                                        <table id="report-data" class="table table-bordered table-striped table-hover" style="width:100%;font-size:13px;">
                                            <thead>
                                                <tr>
                                                    <th colspan="3" style="text-align:center;">
                                                        <?= $data['hdata']['date1']; ?>
                                                    </th>
                                                    <th colspan="3" style="text-align:center;">Day Shift</th>
                                                </tr>
                                                <tr>
                                                    <th>Line</th>
                                                    <th>Model</th>
                                                    <th>Lot</th>
                                                    <th>Plan Qty</th>
                                                    <th>Output Qty</th>
                                                    <th>Variance</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($data['rday1'] as $row) : ?>
                                                    <?php if($row['planqtyd1s1'] != 0): ?>
                                                    <tr>
                                                        <td><?= $row['linename']; ?></td>
                                                        <td><?= $row['model']; ?></td>
                                                        <td><?= $row['lot_number']; ?></td>
                                                        <td style="text-align:right;"><?= $row['planqtyd1s1']; ?></td>
                                                        <?php if($row['qtyoutd1s1'] < $row['planqtyd1s1']): ?>
                                                            <td style="background-color:red;color:white;text-align:right;"><?= $row['qtyoutd1s1']; ?></td>
                                                        <?php elseif($row['qtyoutd1s1'] >= $row['planqtyd1s1'] && $row['qtyoutd1s1'] > 0): ?>
                                                            <td style="background-color:green;color:white;text-align:right;">
                                                                <?= $row['qtyoutd1s1']; ?>
                                                            </td>
                                                        <?php else: ?>
                                                            <td style="text-align:right;"><?= $row['qtyoutd1s1']; ?></td>
                                                        <?php endif; ?>

                                                        <td style="text-align:right;"><?= $row['qtyoutd1s1'] - $row['planqtyd1s1']; ?></td>
                                                    </tr>
                                                    <?php endif; ?>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                    <?php endif; ?>
                                    

                                    <?php if($data['hdata']['date1'] == date('Y-m-d')): ?>
                                    <div class="col-lg-6">
                                        <table id="report-data-night-shift" class="table table-bordered table-striped table-hover" style="width:100%;font-size:13px;">
                                            <thead>
                                                <tr>
                                                    <th colspan="3" style="text-align:center;">

                                                        <?= $data['hdata']['date1']; ?>
                                                    
                                                    </th>
                                                    <th colspan="3" style="text-align:center;">Night Shift</th>
                                                </tr>
                                                <tr>
                                                    <th>Line</th>
                                                    <th>Model</th>
                                                    <th>Lot</th>
                                                    <th>Plan Qty</th>
                                                    <th>Output Qty</th>
                                                    <th>Variance</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($data['rday1'] as $row) : ?>
                                                    <?php if($row['planqtyd1s2'] != 0): ?>
                                                    <tr>
                                                        <td><?= $row['linename']; ?></td>
                                                        <td><?= $row['model']; ?></td>
                                                        <td><?= $row['lot_number']; ?></td>      
                                                        <td style="text-align:right;"><?= $row['planqtyd1s2']; ?></td>                                                  
                                                        <?php if($row['qtyoutd1s2'] < $row['planqtyd1s2']): ?>
                                                            <td style="background-color:red;color:white;text-align:right;">
                                                                <?= $row['qtyoutd1s2']; ?>
                                                            </td>
                                                        <?php elseif($row['qtyoutd1s2'] >= $row['planqtyd1s2'] && $row['qtyoutd1s2'] > 0): ?>
                                                            <td style="background-color:green;color:white;text-align:right;">
                                                                <?= $row['qtyoutd1s2']; ?>
                                                            </td>
                                                        <?php else: ?>
                                                            <td style="text-align:right;"><?= $row['qtyoutd1s2']; ?></td>
                                                        <?php endif; ?>
                                                        <td style="text-align:right;"><?= $row['qtyoutd1s2'] - $row['planqtyd1s2']; ?></td>
                                                    </tr>
                                                    <?php endif; ?>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                    <?php else: ?>
                                        <?php if($data['chour']['serverhour'] >= 0 && $data['chour']['serverhour'] < 7): ?>
                                            <div class="col-lg-6">
                                                <table id="report-data-night-shift" class="table table-bordered table-striped table-hover" style="width:100%;font-size:13px;">
                                                    <thead>
                                                        <tr>
                                                            <th colspan="3" style="text-align:center;">

                                                                <?= $data['hdata']['date1']; ?>
                                                            
                                                            </th>
                                                            <th colspan="3" style="text-align:center;">Night Shift</th>
                                                        </tr>
                                                        <tr>
                                                            <th>Line</th>
                                                            <th>Model</th>
                                                            <th>Lot</th>
                                                            <th>Plan Qty</th>
                                                            <th>Output Qty</th>
                                                            <th>Variance</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php foreach ($data['rday1'] as $row) : ?>
                                                            <?php if($row['planqtyd1s2'] != 0): ?>
                                                            <tr>
                                                                <td><?= $row['linename']; ?></td>
                                                                <td><?= $row['model']; ?></td>
                                                                <td><?= $row['lot_number']; ?></td>      
                                                                <td style="text-align:right;"><?= $row['planqtyd1s2']; ?></td>                                                  
                                                                <?php if($row['qtyoutd1s2'] < $row['planqtyd1s2']): ?>
                                                                    <td style="background-color:red;color:white;text-align:right;">
                                                                        <?= $row['qtyoutd1s2']; ?>
                                                                    </td>
                                                                <?php elseif($row['qtyoutd1s2'] >= $row['planqtyd1s2'] && $row['qtyoutd1s2'] > 0): ?>
                                                                    <td style="background-color:green;color:white;text-align:right;">
                                                                        <?= $row['qtyoutd1s2']; ?>
                                                                    </td>
                                                                <?php else: ?>
                                                                    <td style="text-align:right;"><?= $row['qtyoutd1s2']; ?></td>
                                                                <?php endif; ?>
                                                                <td style="text-align:right;"><?= $row['qtyoutd1s2'] - $row['planqtyd1s2']; ?></td>
                                                            </tr>
                                                            <?php endif; ?>
                                                        <?php endforeach; ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        <?php endif; ?>
                                    <?php endif; ?>

                                    <!-- View Day 2 -->
                                    <?php if($data['hdata']['date2'] != date('Y-m-d')): ?>
                                        <?php if($data['chour']['serverhour'] >= 17 && $data['chour']['serverhour'] <= 24): ?>
                                        <div class="col-lg-6">
                                            <table id="report-data" class="table table-bordered table-striped table-hover" style="width:100%;font-size:13px;">
                                                <thead>
                                                    <tr>
                                                        <th colspan="3" style="text-align:center;">
                                                            <?= $data['hdata']['date2']; ?>
                                                        </th>
                                                        <th colspan="3" style="text-align:center;">Day Shift</th>
                                                    </tr>
                                                    <tr>
                                                        <th>Line</th>
                                                        <th>Model</th>
                                                        <th>Lot</th>
                                                        <th>Plan Qty</th>
                                                        <th>Output Qty</th>
                                                        <th>Variance</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($data['rday2'] as $row) : ?>
                                                        <?php if($row['planqtyd2s1'] != 0): ?>
                                                        <tr>
                                                            <td><?= $row['linename']; ?></td>
                                                            <td><?= $row['model']; ?></td>
                                                            <td><?= $row['lot_number']; ?></td>
                                                            <td style="text-align:right;"><?= $row['planqtyd2s1']; ?></td>
                                                            <?php if($row['qtyoutd2s1'] < $row['planqtyd2s1']): ?>
                                                                <td style="background-color:red;color:white;text-align:right;"><?= $row['qtyoutd2s1']; ?></td>
                                                            <?php elseif($row['qtyoutd2s1'] >= $row['planqtyd2s1'] && $row['qtyoutd2s1'] > 0): ?>
                                                                <td style="background-color:green;color:white;text-align:right;">
                                                                    <?= $row['qtyoutd2s1']; ?>
                                                                </td>
                                                            <?php else: ?>
                                                                <td style="text-align:right;"><?= $row['qtyoutd2s1']; ?></td>
                                                            <?php endif; ?>

                                                            <td style="text-align:right;"><?= $row['qtyoutd2s1'] - $row['planqtyd2s1']; ?></td>
                                                        </tr>
                                                        <?php endif; ?>
                                                    <?php endforeach; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <?php if($data['chour']['serverhour'] >= 0 && $data['chour']['serverhour'] < 7): ?>
                                            <div class="col-lg-6">
                                                <table id="report-data" class="table table-bordered table-striped table-hover" style="width:100%;font-size:13px;">
                                                    <thead>
                                                        <tr>
                                                            <th colspan="3" style="text-align:center;">
                                                                <?= $data['hdata']['date2']; ?>
                                                            </th>
                                                            <th colspan="3" style="text-align:center;">Day Shift</th>
                                                        </tr>
                                                        <tr>
                                                            <th>Line</th>
                                                            <th>Model</th>
                                                            <th>Lot</th>
                                                            <th>Plan Qty</th>
                                                            <th>Output Qty</th>
                                                            <th>Variance</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php foreach ($data['rday2'] as $row) : ?>
                                                            <?php if($row['planqtyd2s1'] != 0): ?>
                                                            <tr>
                                                                <td><?= $row['linename']; ?></td>
                                                                <td><?= $row['model']; ?></td>
                                                                <td><?= $row['lot_number']; ?></td>
                                                                <td style="text-align:right;"><?= $row['planqtyd2s1']; ?></td>
                                                                <?php if($row['qtyoutd2s1'] < $row['planqtyd2s1']): ?>
                                                                    <td style="background-color:red;color:white;text-align:right;"><?= $row['qtyoutd2s1']; ?></td>
                                                                <?php elseif($row['qtyoutd2s1'] >= $row['planqtyd2s1'] && $row['qtyoutd2s1'] > 0): ?>
                                                                    <td style="background-color:green;color:white;text-align:right;">
                                                                        <?= $row['qtyoutd2s1']; ?>
                                                                    </td>
                                                                <?php else: ?>
                                                                    <td style="text-align:right;"><?= $row['qtyoutd2s1']; ?></td>
                                                                <?php endif; ?>

                                                                <td style="text-align:right;"><?= $row['qtyoutd2s1'] - $row['planqtyd2s1']; ?></td>
                                                            </tr>
                                                            <?php endif; ?>
                                                        <?php endforeach; ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        <?php endif; ?>
                                    <?php endif; ?>

                                    <?php if($data['ctime']['servertime'] >= $nightShift && $data['hdata']['date2'] == date('Y-m-d')): ?>
                                    <div class="col-lg-6">
                                        <?= $data['ctime']['servertime']. ' >= '. $nightShift; ?>
                                        <table id="report-data-night-shift" class="table table-bordered table-striped table-hover" style="width:100%;font-size:13px;">
                                            <thead>
                                                <tr>
                                                    <th colspan="3" style="text-align:center;">
                                                        <?= $data['hdata']['date2']; ?>
                                                    </th>
                                                    <th colspan="3" style="text-align:center;">Night Shift</th>
                                                </tr>
                                                <tr>
                                                    <th>Line</th>
                                                    <th>Model</th>
                                                    <th>Lot</th>
                                                    <th>Plan Qty</th>
                                                    <th>Output Qty</th>
                                                    <th>Variance</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($data['rday2'] as $row) : ?>
                                                    <?php if($row['planqtyd2s2'] != 0): ?>
                                                    <tr>
                                                        <td><?= $row['linename']; ?></td>
                                                        <td><?= $row['model']; ?></td>
                                                        <td><?= $row['lot_number']; ?></td>      
                                                        <td style="text-align:right;"><?= $row['planqtyd2s2']; ?></td>                                                  
                                                        <?php if($row['qtyoutd2s2'] < $row['planqtyd2s2']): ?>
                                                            <td style="background-color:red;color:white;text-align:right;">
                                                                <?= $row['qtyoutd2s2']; ?>
                                                            </td>
                                                        <?php elseif($row['qtyoutd2s2'] >= $row['planqtyd2s2'] && $row['qtyoutd2s2'] > 0): ?>
                                                            <td style="background-color:green;color:white;text-align:right;">
                                                                <?= $row['qtyoutd2s2']; ?>
                                                            </td>
                                                        <?php else: ?>
                                                            <td style="text-align:right;"><?= $row['qtyoutd2s2']; ?></td>
                                                        <?php endif; ?>
                                                        <td style="text-align:right;"><?= $row['qtyoutd2s2'] - $row['planqtyd2s2']; ?></td>
                                                    </tr>
                                                    <?php endif; ?>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                    <?php endif; ?>
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