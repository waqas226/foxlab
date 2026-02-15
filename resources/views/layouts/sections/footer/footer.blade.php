@php
$containerFooter = (isset($configData['contentLayout']) && $configData['contentLayout'] === 'compact') ? 'container-xxl' : 'container-fluid';
@endphp

<!--Footer-->
<footer class="content-footer footer bg-footer-theme">
  <div class="{{ $containerFooter }}">
    <div class="footer-container d-flex align-items-center justify-content-between py-4 flex-md-row flex-column">
      <div class="text-body">
      <div class="footer-link me-4 text-primary">Address<i class="ti ti-map"></i> : <span class="text-black">{{$configData['siteVariable']->contact_address}}</span> </div>
      </div>
      <div class="d-none d-lg-inline-block">
      <div class="footer-link me-4 text-primary">Mobile<i class="ti ti-phone"></i> : <span class="text-black">{{$configData['siteVariable']->contact_mobile}}</span> </div>
      <div class="footer-link me-4 text-primary">Office<i class="ti ti-phone"></i> : <span class="text-black">{{$configData['siteVariable']->contact_office}}</span> </div>
         </div>
    </div>
  </div>
</footer>
<!--/ Footer-->
<script>
    let timeout;

    function resetTimer() {
        clearTimeout(timeout);
        timeout = setTimeout(() => {
            window.location.href = '/logout';
        }, 60 * 1000 * {{$configData['siteVariable']->idle_timeout}}); // 5 minutes
    }

    window.onload = resetTimer;
    document.onmousemove = resetTimer;
    document.onkeypress = resetTimer;
</script>
