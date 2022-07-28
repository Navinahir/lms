<div class="pull-right col-sm-12 col-md-7 col-lg-6 search_view">
    <form action="<?php echo site_url('/dashboard/search'); ?>" method="get" name="search_all" id="search_all">
        <div class="row">
            <div class="col-md-5 col-xs-12">
                <div class="form-group has-feedback select_form_group">
                    <select data-placeholder="Select a option..." class="select select-size-sm" id="search_type" name="search_type">
                        <option></option>
                        <option value="fcc" <?php echo ($this->input->get('search_type') && $this->input->get('search_type') == 'fcc') ? 'selected="selected"' : '' ?>>Search By FCC ID</option>
                        <option value="chip" <?php echo ($this->input->get('search_type') && $this->input->get('search_type') == 'chip') ? 'selected="selected"' : '' ?>>Search By Chip ID</option>
                        <option value="part" <?php echo ($this->input->get('search_type') && $this->input->get('search_type') == 'part') ? 'selected="selected"' : '' ?>>Search By Part No</option>
                        <option value="cust" <?php echo ($this->input->get('search_type') && $this->input->get('search_type') == 'cust') ? 'selected="selected"' : '' ?>>Search By Customer</option>
                        <option value="vin" <?php echo ($this->input->get('search_type') && $this->input->get('search_type') == 'vin') ? 'selected="selected"' : '' ?>>Search By VIN</option>
                    </select>
                </div>
            </div>
            <div class="col-md-7 col-xs-12">
                <div class="form-group has-feedback">
                    <div class="input-group">
                        <input class="form-control" name="q" value="<?php echo ($this->input->get('q')) ? $this->input->get('q') : '' ?>"/>
                        <span class="input-group-btn">
                            <button class="btn bg-blue custom_search_button" type="submit"><i class="icon-search4 text-size-base"></i></button>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
<style>
    .search_view{
        padding: 10px 0;
        margin-bottom: -20px;
    }
</style>