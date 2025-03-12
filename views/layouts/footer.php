<footer class="main-footer bg-dark text-light py-3">
    <div class="container d-flex justify-content-between align-items-center flex-wrap">
        <div>
            <strong>&copy; <span id="year"></span> 
                <a href="https://adminlte.io" target="_blank" class="text-light text-decoration-none">E-CMV I.T. Corp.</a>
            </strong> 
            - All rights reserved.
        </div>
        <div class="d-flex align-items-center">
            <b>Version 3.1.0</b>
            <span class="ms-2"><i class="fas fa-code-branch"></i></span>
        </div>
    </div>
</footer>

<script>
    document.getElementById("year").textContent = new Date().getFullYear();
</script>
