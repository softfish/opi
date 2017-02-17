<?php
/**
 * This snippet was originally obtained from Bootsnipp and customized
 * for this application.
 * Here is the reference link for the source:
 * 
 * http://bootsnipp.com/snippets/featured/form-process-steps
 *  
 */
    // Do this in the view here, so we can re-use this snippets any where else for
    // other template. All we need is the name of the current item physical status.
    $progressConfig = \Config::get('item.progress_info');
    
    // Look for the status key in the item progress configuration
    // This will load us with the current configuration which is necessary for this display below.
    $statusKey = str_replace(' ', '_', strtolower($itemPhysicalStatus));
    
    $currentConfig = $progressConfig[$statusKey];
?>
<div class="status-snippet container">
    <div class="row bs-wizard" style="border-bottom:0;">
        <div class="col-sm-4 bs-wizard-step {{ $currentConfig['To order']['bar_status'] }}">
          <div class="text-center bs-wizard-stepnum">To Order</div>
          <div class="progress"><div class="progress-bar"></div></div>
          <a href="#" class="bs-wizard-dot"></a>
          <div class="bs-wizard-info text-center">{{ $currentConfig['To order']['bar_info'] }}</div>
        </div>
        
        <div class="col-sm-4 bs-wizard-step {{ $currentConfig['In warehouse']['bar_status'] }}"><!-- complete -->
          <div class="text-center bs-wizard-stepnum">In Warehouse</div>
          <div class="progress"><div class="progress-bar"></div></div>
          <a href="#" class="bs-wizard-dot"></a>
          <div class="bs-wizard-info text-center">{{ $currentConfig['In warehouse']['bar_info'] }}</div>
        </div>
        
        <div class="col-sm-4 bs-wizard-step {{ $currentConfig['Delivered']['bar_status'] }}"><!-- complete -->
          <div class="text-center bs-wizard-stepnum">Delivered</div>
          <div class="progress"><div class="progress-bar"></div></div>
          <a href="#" class="bs-wizard-dot"></a>
          <div class="bs-wizard-info text-center">{{ $currentConfig['Delivered']['bar_info'] }}</div>
        </div>
    </div>
</div>

<!-- Move this to a css file later -->
<style>
.status-snippet {width: 100% !important;}
/*Form Wizard*/
.bs-wizard {border-bottom: solid 1px #e0e0e0; padding: 0 0 10px 0;}
.bs-wizard > .bs-wizard-step {padding: 0; position: relative;}
.bs-wizard > .bs-wizard-step .bs-wizard-stepnum {color: #595959; font-size: 14px; margin-bottom: 5px;}
.bs-wizard > .bs-wizard-step .bs-wizard-info {color: #999; font-size: 12px; padding: 0px 10px;}
.bs-wizard > .bs-wizard-step > .bs-wizard-dot {position: absolute; width: 30px; height: 30px; display: block; background: #fbe8aa; top: 45px; left: 50%; margin-top: -15px; margin-left: -15px; border-radius: 50%;} 
.bs-wizard > .bs-wizard-step > .bs-wizard-dot:after {content: ' '; width: 14px; height: 14px; background: #fbbd19; border-radius: 50px; position: absolute; top: 8px; left: 8px; } 
.bs-wizard > .bs-wizard-step > .progress {position: relative; border-radius: 0px; height: 8px; box-shadow: none; margin: 20px 0;}
.bs-wizard > .bs-wizard-step > .progress > .progress-bar {width:0px; box-shadow: none; background: #fbe8aa;}
.bs-wizard > .bs-wizard-step.complete > .progress > .progress-bar {width:100%;}
.bs-wizard > .bs-wizard-step.active > .progress > .progress-bar {width:50%;}
.bs-wizard > .bs-wizard-step:first-child.active > .progress > .progress-bar {width:0%;}
.bs-wizard > .bs-wizard-step:last-child.active > .progress > .progress-bar {width: 100%;}
.bs-wizard > .bs-wizard-step.disabled > .bs-wizard-dot {background-color: #f5f5f5;}
.bs-wizard > .bs-wizard-step.disabled > .bs-wizard-dot:after {opacity: 0;}
.bs-wizard > .bs-wizard-step:first-child  > .progress {left: 50%; width: 50%;}
.bs-wizard > .bs-wizard-step:last-child  > .progress {width: 50%;}
.bs-wizard > .bs-wizard-step.disabled a.bs-wizard-dot{ pointer-events: none; }
/*END Form Wizard*/
</style>