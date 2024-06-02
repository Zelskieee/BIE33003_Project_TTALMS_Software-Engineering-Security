<?php
session_start();
require 'includes/config.php';

if (isset($_GET['bookid']) && isset($_SESSION['login'])) {
    if (isset($_POST['issue'])) {
        $bookid = $_GET['bookid'];
        $studentid = $_SESSION['login'];
        $isissued = 1;
        $sql = "INSERT INTO issuedbookdetails(StudentID, BookId) VALUES(:studentid, :bookid);
                UPDATE books SET isIssued = :isissued WHERE id = :bookid;";
        $query = $dbh->prepare($sql);
        $query->bindParam(':studentid', $studentid, PDO::PARAM_STR);
        $query->bindParam(':bookid', $bookid, PDO::PARAM_STR);
        $query->bindParam(':isissued', $isissued, PDO::PARAM_STR);
        $query->execute();
        $lastInsertId = $dbh->lastInsertId();
        if ($lastInsertId) {
            $_SESSION['msg'] = "Book issued successfully";
            header('location:issued-books.php');
        } else {
            $_SESSION['error'] = "Something went wrong. Please try again";
            header('location:manage-issued-books.php');
        }
    }
} else {
    $_SESSION['error'] = "Please login to borrow a book";
    header('location:index.php');
}
?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>TTALMS - Student Borrow Book</title>
    <link rel="icon" type="image/x-icon" href="assets\img\icon_ttalms.ico">
    <!-- BOOTSTRAP CORE STYLE  -->
    <link href="assets/css/bootstrap.css" rel="stylesheet" />
    <!-- FONT AWESOME STYLE  -->
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <!-- CUSTOM STYLE  -->
    <link href="assets/css/style.css" rel="stylesheet" />
    <!-- GOOGLE FONT -->
    <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />
    <!-- ICON -->
    <script src="https://kit.fontawesome.com/641ebcf430.js" crossorigin="anonymous"></script>
    <!-- Include slider captcha CSS and JS -->
    <link rel="stylesheet" href="path/to/sliderCaptcha.css" />
    <script src="path/to/sliderCaptcha.js"></script>
    <style>
        input[type="button"] {
            padding: 17px 40px;
            border-radius: 50px;
            cursor: pointer;
            border: 0;
            background-color: white;
            color: black;
            box-shadow: rgb(0 0 0 / 5%) 0 0 8px;
            font-weight: bold;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            font-size: 15px;
            transition: all 0.5s ease;
        }

        input[type="button"]:hover {
            letter-spacing: 3px;
            background-color: #9B00EA;
            color: hsl(0, 0%, 100%);
            box-shadow: rgb(93 24 220) 0px 7px 29px 0px;
        }

        input[type="button"]:active {
            letter-spacing: 3px;
            background-color: #9B00EA;
            color: hsl(0, 0%, 100%);
            box-shadow: rgb(93 24 220) 0px 0px 0px 0px;
            transform: translateY(10px);
            transition: 100ms;
        }

        .btn-primary:hover {
            background-color: white !important;
            color: black !important;
            transition: background-color 0.5s ease, color 0.5s ease;
        }

        @import url('https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700,900&display=swap');

        button:focus,
        input:focus {
            outline: none;
            box-shadow: none;
        }

        a,
        a:hover {
            text-decoration: none;
        }

        .block {
            position: absolute;
            left: 0;
            top: 0;
        }

        .slidercaptcha {
            margin: 0 auto;
            width: 100%;
            height: 300px;
            border-radius: 4px;
            margin-top: 0;
        }

        .slidercaptcha canvas:first-child {
            border-radius: 5px;
            border: 1px solid #e6e8eb;
        }

        .sliderContainer {
            position: relative;
            text-align: center;
            line-height: 40px;
            background: #f7f9fa;
            color: #45494c;
            border-radius: 2px;
        }

        .sliderbg {
            position: absolute;
            left: 0;
            right: 0;
            top: 0;
            background-color: #f7f9fa;
            height: 40px;
            border-radius: 2px;
            border: 1px solid #e6e8eb;
        }

        .sliderContainer_active .slider {
            top: -1px;
            border: 1px solid #1991FA;
        }

        .sliderContainer_active .sliderMask {
            border-width: 1px 0 1px 1px;
        }

        .sliderContainer_success .slider {
            top: -1px;
            border: 1px solid #02c076;
            background-color: #02c076 !important;
            color: #fff;
        }

        .sliderContainer_success .sliderMask {
            border: 1px solid #52CCBA;
            border-width: 1px 0 1px 1px;
            background-color: #D2F4EF;
        }

        .sliderContainer_success .sliderIcon:before {
            content: "\f00c";
        }

        .sliderContainer_fail .slider {
            top: -1px;
            border: 1px solid #f35c59;
            background-color: #f35c59;
            color: #fff;
        }

        .sliderContainer_fail .sliderMask {
            border: 1px solid #f35c59;
            background-color: #f7dcdd;
            border-width: 1px 0 1px 1px;
        }

        .sliderContainer_fail .sliderIcon:before {
            content: "\f00d";
        }

        .sliderContainer_active .sliderText,
        .sliderContainer_success .sliderText,
        .sliderContainer_fail .sliderText {
            display: none;
        }

        .sliderMask {
            position: absolute;
            left: 0;
            top: 0;
            height: 40px;
            border: 0 solid #d1e9fe;
            background: #d1e9fe;
            border-radius: 2px;
        }

        .slider {
            position: absolute;
            top: 0;
            left: 0;
            width: 40px;
            height: 40px;
            background: #fff;
            box-shadow: 0 0 3px rgba(0, 0, 0, 0.3);
            cursor: pointer;
            transition: background .2s linear;
            border-radius: 2px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .slider:hover {
            background: #009efb;
            color: #fff;
            border-color: #009efb;
        }

        .slider:hover .sliderIcon {
            background-position: 0 -13px;
        }

        .sliderText {
            position: relative;
        }

        .sliderIcon {}

        .refreshIcon {
            position: absolute;
            right: 5px;
            top: 5px;
            cursor: pointer;
            padding: 6px;
            color: #fff;
            background-color: #D896FA;
            font-size: 14px;
            border-radius: 50px;
        }

        .refreshIcon:hover {
            color: #fff;
        }
    </style>
</head>

<body>
    <!------MENU SECTION START-->
    <?php include('includes/header.php'); ?>
    <!-- MENU SECTION END-->
    <div class="content-wrapper">
        <div class="container">
            <?php include('includes/check_verification.php'); ?>
            <div class="row pad-botm">
                <div class="col-md-12">
                    <h4 class="header-line" style="text-align: center; position: relative;"><i class="fa-solid fa-book-bookmark fa-beat"></i> Borrow Book</h4>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3 col-md-offset-1">
                    <div class="panel panel-danger" style="border-radius: 10px; border-color: #9B00EA;">
                        <div class="panel-heading" style="background-color: #9B00EA; color: #fff; border-top-left-radius: 10px; border-top-right-radius: 10px; text-align: center; font-weight: bold; border-color: #9B00EA;">
                            Borrow Book
                        </div>
                        <div class="panel-body bg-warning" style="background-color: white; ">
                            <form id="borrowForm" role="form" method="post">
                                <div class="form-group text-center">
                                    <label>Are you sure you want to borrow this book?</label>
                                </div>
                                <div class="text-center">
                                    <input type="button" name="issue" value="Yes" id="issueBtn">
                                </div>
                                <div class="text-center">
                                    <a href="listed-books.php" class="btn btn-primary" style="background-color: black; border-radius: 15px; margin-top: 20px; border-color: black; color: white;"><i class="fa-solid fa-arrow-left fa-beat-fade"></i> NO</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <!-- book details -->
                <?php
                $bookid = $_GET['bookid'];
                $sql = "SELECT distinct books.BookName,books.id,authors.AuthorName,books.bookImage,books.isIssued, books.ISBNNumber, books.BookPrice, c.CategoryName FROM books
                        join authors on authors.id=books.AuthorId
                        join category c on c.id=books.CatId
                        where books.id=:bookid;";
                $query = $dbh->prepare($sql);
                $query->bindParam(':bookid', $bookid, PDO::PARAM_STR);
                $query->execute();
                $results = $query->fetchAll(PDO::FETCH_OBJ);
                $cnt = 1;
                if ($query->rowCount() > 0) {
                    foreach ($results as $result) {
                ?>
                        <div class="col-md-8 col-sm-3">
                            <div class="panel panel-primary">
                                <div class="panel-heading" style="text-align: center; font-weight: bold;">
                                    <?php echo htmlentities($result->BookName); ?>
                                </div>
                                <div class="panel-body text-center">
                                    <img src="admin/bookimage/<?php echo htmlentities($result->bookImage); ?>" style="width: 150px; height: 200px;" />
                                    <h4><b>Author:</b> <?php echo htmlentities($result->AuthorName); ?></h4>
                                    <h4><b>Category:</b> <?php echo htmlentities($result->CategoryName); ?></h4>
                                    <h4><b>ISBN Number:</b> <?php echo htmlentities($result->ISBNNumber); ?></h4>
                                    <h4><b>Price:</b> RM<?php echo htmlentities($result->BookPrice); ?></h4>
                                </div>
                        <?php }
                } ?>
                            </div>
                        </div>
            </div>
        </div>
    </div>
    <!-- CONTENT-WRAPPER SECTION END-->
    <?php include('includes/footer.php'); ?>
    <!-- FOOTER SECTION END-->
    <!-- JAVASCRIPT FILES PLACED AT THE BOTTOM TO REDUCE THE LOADING TIME  -->
    <!-- CORE JQUERY  -->
    <script src="assets/js/jquery-1.10.2.js"></script>
    <!-- BOOTSTRAP SCRIPTS  -->
    <script src="assets/js/bootstrap.js"></script>
    <!-- CUSTOM SCRIPTS  -->
    <script src="assets/js/custom.js"></script>
    <!-- SLIDER CAPTCHA SCRIPT -->
    <script>
        (function() {
            "use strict";

            function u(n) {
                var i = document.getElementById(n.id),
                    r = typeof n == "object" && n;
                return new t(i, r);
            }

            var r = function() {
                var u = arguments.length,
                    n = arguments[0] || {},
                    t, i, r;
                for (typeof n != "object" && typeof n != "function" && (n = {}), u == 1 && (n = this, t--), t = 1; t < u; t++) {
                    i = arguments[t];
                    for (r in i) Object.prototype.hasOwnProperty.call(i, r) && (n[r] = i[r]);
                }
                return n;
            },
            i = function(n) {
                return typeof n == "function" && typeof n.nodeType != "number";
            },
            t = function(n, i) {
                this.$element = n;
                this.options = r({}, t.DEFAULTS, i);
                this.$element.style.position = "relative";
                this.$element.style.width = this.options.width + "px";
                this.$element.style.margin = "0 auto";
                this.init();
            },
            n;
            t.VERSION = "1.0";
            t.Author = "argo@163.com";
            t.DEFAULTS = {
                width: 280,
                height: 155,
                PI: Math.PI,
                sliderL: 42,
                sliderR: 9,
                offset: 5,
                loadingText: "Loading...",
                failedText: "Try again",
                barText: "Slide right to fill",
                repeatIcon: "fa fa-repeat",
                maxLoadCount: 3,
                localImages: function() {
                    return "images/Pic" + Math.round(Math.random() * 4) + ".jpg";
                },
                verify: function(n, t) {
                    var i = !1;
                    return $.ajax({
                        url: t,
                        data: {
                            datas: JSON.stringify(n)
                        },
                        dataType: "json",
                        type: "post",
                        async: !1,
                        success: function(n) {
                            i = JSON.stringify(n);
                            console.log("Result: " + i);
                        }
                    }), i;
                },
                remoteUrl: null
            };
            window.sliderCaptcha = u;
            window.sliderCaptcha.Constructor = t;
            n = t.prototype;
            n.init = function() {
                this.initDOM();
                this.initImg();
                this.bindEvents();
            };
            n.initDOM = function() {
                var n = function(n, t) {
                    var i = document.createElement(n);
                    return i.className = t, i;
                },
                v = function(n, t) {
                    var i = document.createElement("canvas");
                    return i.width = n, i.height = t, i;
                },
                f = v(this.options.width - 2, this.options.height),
                    e = f.cloneNode(!0),
                    t = n("div", "sliderContainer"),
                    l = n("i", "refreshIcon " + this.options.repeatIcon),
                    o = n("div", "sliderMask"),
                    y = n("div", "sliderbg"),
                    s = n("div", "slider"),
                    a = n("i", "fa fa-arrow-right sliderIcon"),
                    h = n("span", "sliderText"),
                    u, c;
                e.className = "block";
                h.innerHTML = this.options.barText;
                u = this.$element;
                u.appendChild(f);
                u.appendChild(l);
                u.appendChild(e);
                s.appendChild(a);
                o.appendChild(s);
                t.appendChild(y);
                t.appendChild(o);
                t.appendChild(h);
                u.appendChild(t);
                c = {
                    canvas: f,
                    block: e,
                    sliderContainer: t,
                    refreshIcon: l,
                    slider: s,
                    sliderMask: o,
                    sliderIcon: a,
                    text: h,
                    canvasCtx: f.getContext("2d"),
                    blockCtx: e.getContext("2d")
                };
                i(Object.assign) ? Object.assign(this, c) : r(this, c);
            };
            n.initImg = function() {
                var n = this,
                    f = window.navigator.userAgent.indexOf("Trident") > -1,
                    r = this.options.sliderL + this.options.sliderR * 2 + 3,
                    e = function(t, i) {
                        var r = n.options.sliderL,
                            o = n.options.sliderR,
                            s = n.options.PI,
                            u = n.x,
                            e = n.y;
                        t.beginPath();
                        t.moveTo(u, e);
                        t.arc(u + r / 2, e - o + 2, o, .72 * s, 2.26 * s);
                        t.lineTo(u + r, e);
                        t.arc(u + r + o - 2, e + r / 2, o, 1.21 * s, 2.78 * s);
                        t.lineTo(u + r, e + r);
                        t.lineTo(u, e + r);
                        t.arc(u + o - 2, e + r / 2, o + .4, 2.76 * s, 1.24 * s, !0);
                        t.lineTo(u, e);
                        t.lineWidth = 2;
                        t.fillStyle = "rgba(255, 255, 255, 0.7)";
                        t.strokeStyle = "rgba(255, 255, 255, 0.7)";
                        t.stroke();
                        t[i]();
                        t.globalCompositeOperation = f ? "xor" : "destination-over";
                    },
                    o = function(n, t) {
                        return Math.round(Math.random() * (t - n) + n);
                    },
                    t = new Image,
                    u;
                t.crossOrigin = "Anonymous";
                u = 0;
                t.onload = function() {
                    n.x = o(r + 10, n.options.width - (r + 10));
                    n.y = o(10 + n.options.sliderR * 2, n.options.height - (r + 10));
                    e(n.canvasCtx, "fill");
                    e(n.blockCtx, "clip");
                    n.canvasCtx.drawImage(t, 0, 0, n.options.width - 2, n.options.height);
                    n.blockCtx.drawImage(t, 0, 0, n.options.width - 2, n.options.height);
                    var i = n.y - n.options.sliderR * 2 - 1,
                        u = n.blockCtx.getImageData(n.x - 3, i, r, r);
                    n.block.width = r;
                    n.blockCtx.putImageData(u, 0, i + 1);
                    n.text.textContent = n.text.getAttribute("data-text");
                };
                t.onerror = function() {
                    if (u++, window.location.protocol === "file:" && (u = n.options.maxLoadCount, console.error("can't load pic resource file from File protocal. Please try http or https")), u >= n.options.maxLoadCount) {
                        n.text.textContent = "Load failed";
                        n.classList.add("text-danger");
                        return;
                    }
                    t.src = n.options.localImages();
                };
                t.setSrc = function() {
                    var r = "",
                        e;
                    u = 0;
                    n.text.classList.remove("text-danger");
                    i(n.options.setSrc) && (r = n.options.setSrc());
                    r && r !== "" || (r = "https://picsum.photos/" + n.options.width + "/" + n.options.height + "/?image=" + Math.round(Math.random() * 20));
                    f ? (e = new XMLHttpRequest, e.onloadend = function(n) {
                        var i = new FileReader;
                        i.readAsDataURL(n.target.response);
                        i.onloadend = function(n) {
                            t.src = n.target.result;
                        };
                    }, e.open("GET", r), e.responseType = "blob", e.send()) : t.src = r;
                };
                t.setSrc();
                this.text.setAttribute("data-text", this.options.barText);
                this.text.textContent = this.options.loadingText;
                this.img = t;
            };
            n.clean = function() {
                this.canvasCtx.clearRect(0, 0, this.options.width, this.options.height);
                this.blockCtx.clearRect(0, 0, this.options.width, this.options.height);
                this.block.width = this.options.width;
            };
            n.bindEvents = function() {
                var n = this;
                this.$element.addEventListener("selectstart", function() {
                    return !1;
                });
                this.refreshIcon.addEventListener("click", function() {
                    n.text.textContent = n.options.barText;
                    n.reset();
                    i(n.options.onRefresh) && n.options.onRefresh.call(n.$element);
                });
                var r, u, f = [],
                    t = !1,
                    e = function(i) {
                        n.text.classList.contains("text-danger") || (r = i.clientX || i.touches[0].clientX, u = i.clientY || i.touches[0].clientY, t = !0);
                    },
                    o = function(i) {
                        var o;
                        if (!t) return !1;
                        var s = i.clientX || i.touches[0].clientX,
                            h = i.clientY || i.touches[0].clientY,
                            e = s - r,
                            c = h - u;
                        if (e < 0 || e + 40 > n.options.width) return !1;
                        n.slider.style.left = e - 1 + "px";
                        o = (n.options.width - 60) / (n.options.width - 40) * e;
                        n.block.style.left = o + "px";
                        n.sliderContainer.classList.add("sliderContainer_active");
                        n.sliderMask.style.width = e + 4 + "px";
                        f.push(Math.round(c));
                    },
                    s = function(u) {
                        var o, e;
                        if (!t || (t = !1, o = u.clientX || u.changedTouches[0].clientX, o === r)) return !1;
                        n.sliderContainer.classList.remove("sliderContainer_active");
                        n.trail = f;
                        e = n.verify();
                        e.spliced && e.verified ? (n.sliderContainer.classList.add("sliderContainer_success"), i(n.options.onSuccess) && n.options.onSuccess.call(n.$element)) : (n.sliderContainer.classList.add("sliderContainer_fail"), i(n.options.onFail) && n.options.onFail.call(n.$element), setTimeout(function() {
                            n.text.innerHTML = n.options.failedText;
                            n.reset();
                        }, 1e3));
                    };
                this.slider.addEventListener("mousedown", e);
                this.slider.addEventListener("touchstart", e);
                document.addEventListener("mousemove", o);
                document.addEventListener("touchmove", o);
                document.addEventListener("mouseup", s);
                document.addEventListener("touchend", s);
                document.addEventListener("mousedown", function() {
                    return !1;
                });
                document.addEventListener("touchstart", function() {
                    return !1;
                });
                document.addEventListener("swipe", function() {
                    return !1;
                });
            };
            n.verify = function() {
                var n = this.trail,
                    r = parseInt(this.block.style.left),
                    t = !1;
                if (this.options.remoteUrl !== null) t = this.options.verify(n, this.options.remoteUrl);
                else {
                    var i = function(n, t) {
                            return n + t;
                        },
                        u = function(n) {
                            return n * n;
                        },
                        f = n.reduce(i) / n.length,
                        e = n.map(function(n) {
                            return n - f;
                        }),
                        o = Math.sqrt(e.map(u).reduce(i) / n.length);
                    t = o !== 0;
                }
                return {
                    spliced: Math.abs(r - this.x) < this.options.offset,
                    verified: t
                };
            };
            n.reset = function() {
                this.sliderContainer.classList.remove("sliderContainer_fail");
                this.sliderContainer.classList.remove("sliderContainer_success");
                this.slider.style.left = 0;
                this.block.style.left = 0;
                this.sliderMask.style.width = 0;
                this.clean();
                this.text.setAttribute("data-text", this.text.textContent);
                this.text.textContent = this.options.loadingText;
                this.img.setSrc();
            };
        })();

        var captchaInitialized = false;

        document.getElementById('issueBtn').addEventListener('click', function() {
            $('#captchaModal').modal('show');

            if (!captchaInitialized) {
                var captcha = sliderCaptcha({
                    id: 'captcha',
                    loadingText: 'Loading...',
                    failedText: 'Try again',
                    barText: 'Slide right to fill',
                    repeatIcon: 'fa fa-redo',
                    onSuccess: function() {
                        setTimeout(function() {
                            alert('Captcha Slider successfully verified. Borrowing the book.');
                            $('#captchaModal').modal('hide');
                            var form = document.getElementById('borrowForm');
                            var input = document.createElement('input');
                            input.type = 'hidden';
                            input.name = 'issue';
                            input.value = '1';
                            form.appendChild(input);
                            form.submit();
                        }, 1000);
                    },
                    setSrc: function() {
                        // You can set a specific image source if needed
                    }
                });
                captchaInitialized = true;
            } else {
                captcha.reset();
            }
        });

        $('#captchaModal').on('hidden.bs.modal', function() {
            if (captchaInitialized) {
                captcha.reset();
            }
        });

    </script>

    <!-- Modal HTML -->
    <div id="captchaModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header" style="background-color: #D896FA; border-radius: 6px 6px 0px 0px;">
                    <h5 class="modal-title" style="font-weight: bold;">Security Verification<button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button></h5>
                </div>
                <div class="modal-body">
                    <div id="captcha"></div>
                </div>
            </div>
        </div>
    </div>

</body>

</html>
