<?php if ($this->session->flashdata('success')) { ?>
    <div class="alert alert-success hide-msg flashmsg">
        <button type="button" class="close" data-dismiss="alert"><span>&times;</span><span class="sr-only">Close</span></button>
        <strong><?php echo $this->session->flashdata('success') ?></strong>
    </div>
<?php } ?>
<?php if ($this->session->flashdata('error')) { ?>
    <div class="alert alert-danger hide-msg flashmsg">
        <button type="button" class="close" data-dismiss="alert"><span>&times;</span><span class="sr-only">Close</span></button>
        <strong><?php echo $this->session->flashdata('error') ?></strong>
    </div>
<?php } ?>