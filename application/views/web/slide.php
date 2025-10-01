
<!-- BEGIN: LAYOUT/SLIDERS/REVO-SLIDER-4 -->
<section class="c-layout-revo-slider c-layout-revo-slider-4" dir="ltr" id="home">
  <div class="tp-banner-container ">
    <div class="tp-banner rev_slider" data-version="5.0">
      <ul>
        <?php
        $this->db->order_by('id_slide','DESC');
        $v_slide = $this->db->get('tbl_slide');
        foreach ($v_slide->result() as $key => $value): ?>
        <li data-transition="fade" data-slotamount="1" data-masterspeed="1000">
          <img
          alt=""
          src="<?php echo $value->foto_slide; ?>"
          title="<?php echo $value->ket_slide; ?>"
          data-bgposition="center center"
          data-bgfit="cover"
          data-bgrepeat="no-repeat"
          >
          <div class="tp-caption customin customout"
          data-x="center"
          data-y="center"
          data-hoffset=""
          data-voffset="-50"
          data-speed="500"
          data-start="1000"
          data-transform_idle="o:1;"
          data-transform_in="rX:0.5;scaleX:0.75;scaleY:0.75;o:0;s:500;e:Back.easeInOut;"
          data-transform_out="rX:0.5;scaleX:0.75;scaleY:0.75;o:0;s:500;e:Back.easeInOut;"
          data-splitin="none"
          data-splitout="none"
          data-elementdelay="0.1"
          data-endelementdelay="0.1"
          data-endspeed="600">
              <!-- <p class="c-main-title-circle c-font-25 c-font-bold c-font-center c-font-uppercase c-font-white c-block">
              ayoo bergabung            </p> -->
            </div>
            <div class="tp-caption lft"
            data-x="center"
            data-y="center"
            data-voffset="110"
            data-speed="900"
            data-start="2000"
            data-transform_idle="o:1;"
            data-transform_in="x:100;y:100;rX:120;scaleX:0.75;scaleY:0.75;o:0;s:500;e:Back.easeInOut;"
            data-transform_out="x:100;y:100;rX:120;scaleX:0.75;scaleY:0.75;o:0;s:500;e:Back.easeInOut;" >
            <!-- <a href="#" class="c-action-btn btn btn-lg c-btn-square c-theme-btn c-btn-bold c-btn-uppercase">Learn More</a> -->
          </div>
        </li>
      <?php endforeach; ?>

  </ul>
</div>
</div>
</section><!-- END: LAYOUT/SLIDERS/REVO-SLIDER-4 -->
