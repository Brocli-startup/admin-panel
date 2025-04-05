<!DOCTYPE html>
 <!-- <html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ session()->has('dir') ? session()->get('dir') : 'ltr' , }}">  -->
<html lang="en" onload="pageLoad()">
<head>
    @yield('before_head')
    @include('landing-page.partials._head')


    @yield('after_head')


</head>
<script>
    var frontendLocale = "{{ session()->get('locale') ?? 'en' }}";
    sessionStorage.setItem("local", frontendLocale);
    (function() {
        const savedTheme = localStorage.getItem('data-bs-theme') || 'light';
        document.documentElement.setAttribute('data-bs-theme', savedTheme);
        if (savedTheme === 'dark') {
            document.body.classList.add('dark');
        }
    })();
</script>
<body class="body-bg">


    <span class="screen-darken"></span>

    <div id="loading">
        @include('landing-page.partials.loading')
    </div>


    <main class="main-content" id="landing-app">
        <div class="position-relative">

            @include('landing-page.partials._header')
        </div>
        @yield('content')
    </main>

    @include('landing-page.partials._footer')

    @include('landing-page.partials.cookie')

    @include('landing-page.partials.back-to-top')



  @yield('before_script')
    @include('landing-page.partials._scripts')
    @include('landing-page.partials._currencyscripts')
    @yield('after_script')

    
<script>
    function readMoreBtn() {
    var readMoreBtns = document.querySelectorAll(".readmore-btn");
    var readMoreTexts = document.querySelectorAll(".readmore-text");
    readMoreBtns.forEach(function (btn) {
      btn.addEventListener('click', function () {
        var container = btn.previousElementSibling; // Assuming the <p> is the previous sibling
        if (container.classList.contains('active')) {
          container.classList.remove('active');
          btn.innerHTML = "Read More";
        } else {
          container.classList.add("active");
          btn.innerHTML = "Read less";
        }
      });
    });
  }
  readMoreBtn();
</script>


    <script>
        function pageLoad() {
            var html = localStorage.getItem('data-bs-theme');
            if (html == null) {
                html = 'light';
            }
            if (html == 'light') {
                jQuery('body').addClass('dark');
                $('.darkmode-logo').removeClass('d-none')
                $('.light-logo').addClass('d-none')
            } else {
                jQuery('body').removeClass('dark');
                $('.darkmode-logo').addClass('d-none')
                $('.light-logo').removeClass('d-none')
            }
        }
        pageLoad();

        const savedTheme = localStorage.getItem('data-bs-theme');
        if (savedTheme === 'dark') {
            $('html').attr('data-bs-theme', 'dark');
        } else {
            $('html').attr('data-bs-theme', 'light');
        }

        $('.change-mode').on('click', function() {
            const body = jQuery('body')
            var html = $('html').attr('data-bs-theme');
            console.log('mode ' +html);

            if (html == 'light') {
                body.removeClass('dark');
                $('html').attr('data-bs-theme', 'dark');
                $('.darkmode-logo').addClass('d-none')
                $('.light-logo').removeClass('d-none')
                localStorage.setItem('dark', true)
                localStorage.setItem('data-bs-theme', 'dark')
            } else {

                $('.body-bg').addClass('dark');
                $('html').attr('data-bs-theme', 'light');
                $('.darkmode-logo').removeClass('d-none')
                $('.light-logo').addClass('d-none')
                localStorage.setItem('dark', false)
                localStorage.setItem('data-bs-theme', 'light')
            }

        })

    </script>

    <script>
        $(document).ready(function() {
            $('.textbuttoni').click(function() {
                $(this).prev('.custome-seatei').toggleClass('active');
                if ($(this).text() === '{{ __('landingpage.read_more') }}') {
                    $(this).text('{{ __('landingpage.read_less') }}');
                } else {
                    $(this).text('{{ __('landingpage.read_more') }}');
                }
            });
        });
    </script>

</body>
</html>
