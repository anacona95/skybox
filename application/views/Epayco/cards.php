<div class="x_panel">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h2>
                Selecciona una tarjeta para continuar
            </h2>
        </div>
        <div class="panel-body text-center">
        <?php if ($this->session->flashdata('msgOk')): ?>
                <div class="alert alert-success" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <?php echo $this->session->flashdata('msgOk') ?>
                </div>
                <?php endif;?>
                <?php if ($this->session->flashdata('msgError')): ?>
                <div class="alert alert-danger" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <?php echo $this->session->flashdata('msgError') ?>
                </div>
            <?php endif;?>

            <div class="container">
            <ul class="nav nav-pills pull-right">
                    <li role="presentation">
                        <a href="/pago-en-linea/card-transaction/<?= $orden->id?>">
                        <img src="/public/images/add-payment.svg" alt="add payment" width="45">&nbsp;Pagar con una nueva
                        </a>
                    </li>
                </ul>
                <br>
                <br>

                <div class="row">
                    <div class="cursor-pointer col-md-offset-5 col-md-2 col-xs-10 col-sm-10">
                        <a href="/pago-en-linea/pay-card/<?= $orden->id?>/<?=$card_default->id?>">
                            <img src="/public/images/favorite-card.svg" alt="Mi direccion" width="35">  
                            <h4 class="modal-title">Favorita</h4>
                            <div class="panel panel-default panel-card-1">
                                <div class="panel-body card-defaul">
                                <h4 class="pull-right text-card"><b>***<?= $card_default->mask?></b></h4>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            <?php if($cards==false):?>
                
            <?php else: ?>
                <legend>Otras tarjetas</legend>
                <div class="row">
                    <?php foreach($cards as $key=>$card):?>
                    <div class="cursor-pointer 
                    <?php
                        if($key==0 && count($cards)>=6){
                            echo '';
                        }
                        
                        if($key==0 && count($cards)==5){
                            echo 'col-md-offset-1';
                        }
                        
                        if($key==0 && count($cards)==4){
                            echo 'col-md-offset-2';
                        }

                        if($key==0 && count($cards)==3){
                            echo 'col-md-offset-3';
                        }

                        if($key==0 && count($cards)==2){
                            echo 'col-md-offset-4';
                        }

                        if($key==0 && count($cards)==1){
                            echo 'col-md-offset-5';
                        }
                        
                    ?> col-md-2 col-xs-10 col-sm-10">
                        <a href="/pago-en-linea/pay-card/<?= $orden->id?>/<?=$card->id?>">
                        <img src="/public/images/cambiar.svg" alt="cambiar" width="35">  
                        <h4 class="modal-title">Pagar con esta</h4>
                        <div class="panel panel-default panel-card-<?= rand(2,4);?>">
                            <div class="panel-body card-defaul">
                            <h4 class="pull-right text-card"><b>***<?= $card->mask?></b></h4>
                            </div>
                        </div>
                        </a>
                    </div>
                    <?php endforeach;?>
                </div>
            <?php endif;?>
            </div>
        </div>               
    </div>
</div>
