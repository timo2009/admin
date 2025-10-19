<?php include "../layout/header.php"; ?>
<?php if ($user->checkUserRights("load")): ?>

    <style>
        #upload {
            max-width: 600px;
            margin: 40px auto;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        #upload label.form-label {
            font-weight: 600;
            font-size: 1.1rem;
            display: block;
            margin-bottom: 10px;
            cursor: pointer;
            color: #2c3e50;
        }

        #drop-area {
            border: 3px dashed #3498db;
            border-radius: 12px;
            padding: 40px;
            text-align: center;
            color: #3498db;
            font-size: 1.2rem;
            font-weight: 600;
            transition: background-color 0.3s ease, border-color 0.3s ease;
            user-select: none;
            margin-bottom: 15px;
            cursor: pointer;
        }

        #drop-area.dragover {
            background-color: #eaf6ff;
            border-color: #2980b9;
            color: #2980b9;
        }

        #progressBar {
            width: 100%;
            height: 20px;
            border-radius: 10px;
            margin-top: 15px;
            display: none;
            appearance: none;
            -webkit-appearance: none;
            background-color: #f0f0f0;
            overflow: hidden;
            box-shadow: inset 0 1px 3px rgba(0, 0, 0, .2);
        }

        #progressBar::-webkit-progress-bar {
            background-color: #f0f0f0;
            border-radius: 10px;
        }

        #progressBar::-webkit-progress-value {
            background: linear-gradient(90deg, #2980b9, #3498db);
            border-radius: 10px;
            transition: width 0.3s ease;
        }

        #progressBar::-moz-progress-bar {
            background: linear-gradient(90deg, #2980b9, #3498db);
            border-radius: 10px;
            transition: width 0.3s ease;
        }

        #status {
            margin-top: 10px;
            font-weight: 600;
            color: #34495e;
        }

        input[type="submit"].btn-primary {
            background-color: #2980b9;
            border-color: #2980b9;
            font-weight: 700;
            padding: 10px 25px;
            font-size: 1rem;
            border-radius: 8px;
            width: 100%;
            max-width: 200px;
            transition: background-color 0.3s ease;
            cursor: pointer;
            margin: 20px auto 0 auto;
            display: block;
        }

        input[type="submit"].btn-primary:hover {
            background-color: #1c5980;
            border-color: #1c5980;
        }
        #file {
            opacity: 0;
            position: absolute;
            z-index: -1;
            width: 0;
            height: 0;
        }
    </style>

    <section id="upload">
        <div class="container">
            <div class="row mt-3">
                <div class="col-12 center">

                    <!-- Drag & Drop Bereich -->
                    <label for="file" id="drop-area">
                        Datei hierher ziehen oder klicken, um Dateien auszuwählen
                        <div id="fileList" style="margin-top:10px; font-size: 0.9rem; color: #2c3e50;"></div>
                    </label>

                    <!-- FORMULAR -->
                    <form
                            id="uploadForm"
                            method="post"
                            enctype="multipart/form-data"
                    >
                        <input
                                class="form-control form-control-lg"
                                name="filesToUpload[]"
                                id="file"
                                type="file"
                                multiple
                        />
                        <br>
                        <input
                                type="submit"
                                class="btn btn-primary"
                                name="submit"
                                value="Upload to cloud"
                        />
                    </form>

                    <!-- Fortschrittsbalken -->
                    <progress id="progressBar" value="0" max="100"></progress>
                    <div id="status"></div>

                </div>
            </div>

            <div class="row">
                <div class="col-12 center">

                    <?php
                    $admin = new AdminClass($user);
                    $data = $_POST['submit'];

                    if (isset($data)) {
                        $uploadedFiles = $admin->loadFileUp($data);
                        $uploadedCount = count($uploadedFiles);
                        ?>

                        <div class="alert alert-primary" role="alert">
                            Hochgeladene Dateien: <?php echo $uploadedCount; ?>
                            Du kannst sie dir <?php echo "<a href='list.php'>hier</a>"; ?> ansehen.
                        </div>

                        <?php
                        foreach ($uploadedFiles as $upload) {
                            $type_info = $upload[5];

                            if ($upload[0] != "false") { ?>

                                <div class="alert alert-primary" role="alert">
                                    <b>Filename: </b> <?php echo $upload[0]; ?>
                                    <br>
                                    <b>Type: </b> <?php echo $upload[2]; ?>
                                    <br>
                                    <b>Size: </b> <?php echo $upload[3] / 1024; ?>kb
                                    <br>
                                    Stored in <?php echo $upload[4]; ?>
                                    <hr>
                                    <?php echo "<a href='list.php?type=" . $upload[6] . "'>ansehen</a>"; ?>
                                </div>

                            <?php } elseif ($upload) {
                                ?>
                                <div class="alert alert-danger" role="alert">
                                    <b>Achtung</b> Die Datei <?php echo $upload[1]; ?> existiert schon!<br>Du musst
                                    diese erste löschen!
                                </div>
                                <?php
                            }
                        }
                    }
                    ?>

                </div>
            </div>
        </div>
    </section>

    <script>

        const form = document.getElementById('uploadForm');
        const progressBar = document.getElementById('progressBar');
        const status = document.getElementById('status');
        const dropArea = document.getElementById('drop-area');
        const fileInput = document.getElementById('file');
        fileInput.addEventListener("change", (e) => {
            console.log('Datei ausgewählt:', e.target.files[0]);
        });
        // Drag & Drop Visuals
        dropArea.addEventListener('dragover', (e) => {
            e.preventDefault();
            dropArea.classList.add('dragover');
        });

        dropArea.addEventListener('dragleave', (e) => {
            e.preventDefault();
            dropArea.classList.remove('dragover');
        });

        dropArea.addEventListener('drop', (e) => {
            console.log('event_drop');

            e.preventDefault();
            dropArea.classList.remove('dragover');
            console.log('event_drop');

            if (e.dataTransfer.files.length) {
                fileInput.files = e.dataTransfer.files;
                updateFileList(fileInput.files);
            }
        });


        form.addEventListener('submit', function (e) {
            console.log("submit")
            console.log(fileInput.files)
            e.preventDefault(); // Standard-Submit verhindern

            if (fileInput.files.length === 0) {
                alert('Bitte mindestens eine Datei auswählen!');
                return;
            }

            const formData = new FormData(form);

            const xhr = new XMLHttpRequest();

            xhr.upload.addEventListener('progress', function (e) {
                if (e.lengthComputable) {
                    const percent = (e.loaded / e.total) * 100;
                    progressBar.style.display = 'block';
                    progressBar.value = percent;
                    status.innerText = Math.round(percent) + '% hochgeladen...';
                }
            });

            xhr.upload.addEventListener('load', function () {
                status.innerText = 'Upload abgeschlossen!';
            });

            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4) {
                    if (xhr.status === 200) {
                        status.innerHTML = xhr.responseText; // hier deine HTML-Antwort anzeigen
                        progressBar.style.display = 'none';
                    } else {
                        status.innerText = 'Fehler beim Upload!';
                        progressBar.style.display = 'none';
                    }
                }
            };

            xhr.open('POST', 'upload_handler.php', true);
            formData.append('submit', 'Upload');
            xhr.send(formData);
        });
        const fileList = document.getElementById('fileList');

        function updateFileList(files) {
            if (files.length === 0) {
                fileList.innerHTML = '';
                status.textContent = '';
                return;
            }
            const names = Array.from(files).map(f => f.name);
            fileList.innerHTML = '<strong>Ausgewählte Dateien (' + files.length + '):</strong><br>' + names.join('<br>');
            status.textContent = files.length + ' Datei(en) ausgewählt.';
        }




        fileInput.addEventListener('change', (e) => {
            console.log("change")
            e.preventDefault();
            console.log("change")
            updateFileList(fileInput.files);
        });

    </script>

    <?php include "../layout/footer.php"; ?>
<?php else: ?>
    <div class="alert alert-danger">
        <b>Rechte Fehler!</b> Du hast nicht die Rechte für diesen Teil des Admins
    </div>
<?php endif; ?>
