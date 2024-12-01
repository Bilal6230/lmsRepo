<?php $__env->startSection('content'); ?>
    <style>
        .swal2-icon {
            justify-content: center !important;
            margin: 15px auto 0 !important;
        }

        .custom_cs {
            padding: 0.375rem 5.75rem;

        }

        .att-custom_div {

            background-color: white !important;

        }

        /* Loader Overlay */
        .loader-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.8);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
        }

        /* Loader Spinner */
        .loader-spinner {
            width: 50px;
            height: 50px;
            border: 5px solid #ccc;
            border-top: 5px solid #007bff;
            /* Change color as needed */
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }
    </style>
    <div class="student-info">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Student Info:</h5>
            <button type="button" onclick="backBtn()" class="btn btn-primary my-1 float-right">Back</a>
        </div>
        <div class="row mb-3" style="border-bottom: 1px dashed #6c6c6c">
            <div class="col-md-3 d-flex align-items-center">
                <label for="name">Name:&nbsp;</label>
                <input type="text" name="name" class="fw-bold mb-0 input_field" id="name_s" placeholder="N/A"
                    readonly />
            </div>
            <div class="col-md-3 d-flex align-items-center">
                <label for="marks">Marks:&nbsp;</label>
                <input type="text" name="marks" class="fw-bold mb-0 input_field" id="marks_s" placeholder="N/A"
                    oninput="get_grade(this.value)" />
            </div>
            <div class="col-md-3 d-flex align-items-center">
                <label for="grade">Grade:&nbsp;</label>
                <input type="text" name="grade" class="fw-bold mb-0 input_field" id="grade_s" placeholder="N/A" />
            </div>
            <div class="col-md-3 d-flex align-items-center">
                <label for="comment">Comment:&nbsp;</label>
                <input type="text" name="comment" class="fw-bold mb-0 input_field" id="comment_s" placeholder="N/A" />
            </div>

        </div>
    </div>


    <!-- Loader Component -->
    <div id="loader" class="loader-overlay" style="display: none;">
        <div class="loader-spinner"></div>
    </div>

    <div class="mainSection-title">
        <div class="row">
            <div class="col-md-3 px-0">
                <div class="grade-sidebar ">
                    <div class="grade-header">
                        <h6 class="text-center">Grade: <span class="grade"></span></h6>
                        <div class="d-none" id="chartdiv"></div>
                        <div id="foodiv">
                            <canvas id="foo" height="80" width="150"
                                style="width: 150px; height: 80px;"></canvas>
                        </div>
                        
                        <p class="text-white"><strong>Percentage:</strong> <span class="percentage"></span></p>
                        <p class="text-white"><strong>Letter Grade:</strong> <span></span></p>
                        <p class="text-white"><strong>IB Grade:</strong> <span></span></p>
                        <p class="text-center navyblue ">
                            AI Reliability Index: <span class="ai_relaiability"></span>
                        </p>
                    </div>
                    <div class="grade-body">
                        <div class="upload-btns">
                            <div class="upload-btn-wrapper ">
                                <button class="upload-btn">Upload Rubric</button>
                                <input type="file" name="csvFile" id="csvFile" accept=".csv" />
                            </div>
                            <?php if(isset($rubicFilesNames)): ?>
                                <div class="upload-btn-wrapper grade-wrap">
                                    <button class="upload-btn" id="grade_tab">Select Grade</button>
                                    <div class="custom-dropdown rubicFileNames" id="dropdown-menu">
                                        <ul>
                                            <?php $__currentLoopData = $rubicFilesNames; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rubicFilesName): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <li data-value="grade1"><?php echo e($rubicFilesName ?? ''); ?></li>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </ul>
                                    </div>
                                </div>
                            <?php endif; ?>

                        </div>
                        <div class="upload-btn-wrapper w-100 mt-3">
                            <button type="button" class="upload-btn w-100" data-bs-toggle="modal"
                                data-bs-target="#assignmentModal">
                                Insert Assignment Instructions
                            </button>
                        </div>
                        <p class="up-h">Upload Assignment:</p>
                        <div class="upload-btns">
                            <div class="upload-btn-wrapper">
                                <button class="upload-btn">PDF</button>
                                <input type="file" id="pdfFile" name="myfile" accept="application/pdf" />
                            </div>

                            <div class="upload-btn-wrapper">
                                <button class="upload-btn">Image</button>
                                <input type="file" id="imageFile" name="myfile"
                                    accept="image/jpeg, image/png, image/jpg" />
                            </div>

                            <div class="upload-btn-wrapper">
                                <button class="upload-btn">Raw Text</button>
                                <input type="file" id="textFile" name="myfile" accept="text/plain" />
                            </div>

                        </div>
                        <button class="btn btn-light rounded-pill text-black w-100 px-5 my-3 startAssessment">Start
                            Assessment </button>
                    </div>
                </div>
            </div>
            <div class="col-md-9">
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="preview-h">Assignment Preview:</h6>
                        <textarea name="feedback" class="assignment-content"></textarea>
                    </div>
                    <div class="col-md-6">
                        <h6 class="preview-h">Feedback Preview:</h6>
                        <textarea name="feedback" class="feedback-content"></textarea>

                    </div>
                    <div class="col-md-12 text-end">
                        <button class="btn btn-theme rounded-pill px-4 py-3 exportPdf">Export as PDF</button>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <div class="modal fade" id="assignmentModal" tabindex="-1" aria-labelledby="assignmentModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="assignmentModalLabel">Assignment Instructions</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="assignmentForm">
                        <!-- Course Name Field -->
                        <div class="mb-3">
                            <label for="courseName" class="form-label">Course Name</label>
                            <input type="text" class="form-control" id="courseName" name="courseName"
                                placeholder="Enter course name">
                            <span class="error text-danger"></span>
                        </div>

                        <!-- Assignment Instructions Field -->
                        <div class="mb-3">
                            <label for="assignmentInstructions" class="form-label">Assignment Instructions</label>
                            <textarea class="form-control" id="assignmentInstructions" name="assignmentInstructions" rows="4"
                                placeholder="Enter assignment instructions"></textarea>
                            <span class="error text-danger"></span>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary saveInstruction" form="assignmentForm">Save
                        Instructions</button>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script src="https://cdn.amcharts.com/lib/4/core.js"></script>
<script src="https://cdn.amcharts.com/lib/4/charts.js"></script>
<script src="https://cdn.amcharts.com/lib/4/themes/animated.js"></script>

<script type="text/javascript">
    "use strict";
    //frontend script
    document.addEventListener('DOMContentLoaded', function() {
        const button = document.getElementById('grade_tab');
        const dropdown = document.getElementById('dropdown-menu');

        // Toggle dropdown on button click
        button.addEventListener('click', function(event) {
            event.stopPropagation(); // Prevent body click event from firing
            dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block';
        });

        // Close dropdown when clicking outside
        document.body.addEventListener('click', function() {
            dropdown.style.display = 'none';
        });

        // Handle item selection in dropdown
        dropdown.querySelectorAll('li').forEach(function(item) {
            item.addEventListener('click', function(event) {
                button.textContent = item.textContent;
                dropdown.style.display = 'none';
            });
        });
    });

    function showLoader() {
        $("#loader").show();
    }

    function hideLoader() {
        $("#loader").hide();
    }
    $(document).ready(function() {
        displayUserInformation();


        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $('#pdfFile').change(function() {
            var file = $('#pdfFile')[0].files[0];
            handlefile(file, "pdf");
            $(this).val('');

        });
        $('#imageFile').change(function() {
            var file = $('#imageFile')[0].files[0];
            handlefile(file, "image");
            $(this).val('');

        });
        $('#textFile').change(function() {
            var file = $('#textFile')[0].files[0];
            const reader = new FileReader();
            reader.readAsText(file);
            reader.onload = (e) => {
                const contents = e.target.result;
                $(".assignment-content").html(contents)
            };
        });
        $(document).on("change", "#csvFile", function() {
            console.log("hits");
            var file = $('#csvFile')[0].files[0];
            handleCSV(file);
            $('#csvFile').val('');
        });

        $(document).on('click', '.exportPdf', function() {
            var feedback = $(".feedback-content").length ? $(".feedback-content").val() : "";
            var grade = $(".grade").length ? $(".grade").text() : "";
            var percentage = $(".percentage").length ? $(".percentage").text() : "";
            var reliabilityIndex = $(".ai_relaiability").length ? $(".ai_relaiability").text() : "";
            var assignmentCntent = $(".assignment-content").length ? $(".assignment-content").val() :
                "";

            handleExportPdf(feedback, grade, percentage, reliabilityIndex, assignmentCntent)
        })

        function handleExportPdf(feedback, grade, percentage, reliabilityIndex, assignmentCntent) {
            if (assignmentCntent == "" || feedback == "") {
                handleError("Please first submit your Assignment!");
                return;
            }
            var student = JSON.parse(localStorage.getItem('student_data'));
            var studentName = "";
            var studentMark = "";
            var studentGrade = "";
            var studentComent = "";
            if (student.name) {
                studentName = student.name;
            }
            if (student.mark) {
                studentMark = student.mark;
            }
            if (student.grade) {
                studentGrade = student.grade;
            }
            if (student.comment) {
                studentComent = student.comment;
            }
            var form = $('<form>', {
                action: '<?php echo e(route('export.pdf')); ?>',
                method: 'POST',
                target: '_blank'
            }).append(
                $('<input>', {
                    type: 'hidden',
                    name: 'feedback',
                    value: feedback
                }),
                $('<input>', {
                    type: 'hidden',
                    name: 'grade',
                    value: grade
                }),
                $('<input>', {
                    type: 'hidden',
                    name: 'percentage',
                    value: percentage
                }),
                $('<input>', {
                    type: 'hidden',
                    name: 'reliabilityIndex',
                    value: reliabilityIndex
                }),
                $('<input>', {
                    type: 'hidden',
                    name: 'assignmentCntent',
                    value: assignmentCntent
                }),
                $('<input>', {
                    type: 'hidden',
                    name: 'studentName',
                    value: studentName
                }),
                $('<input>', {
                    type: 'hidden',
                    name: 'studentMark',
                    value: studentMark
                }),
                $('<input>', {
                    type: 'hidden',
                    name: ' ',
                    value: studentGrade
                }),
                $('<input>', {
                    type: 'hidden',
                    name: 'studentComent',
                    value: studentComent
                }),
                $('<input>', {
                    type: 'hidden',
                    name: '_token',
                    value: '<?php echo e(csrf_token()); ?>'
                })
            );

            form.appendTo('body').submit().remove();
        }

        function handlefile(file, type) {
            if (file) {
                showLoader();
                var allowedFormats = ['application/pdf', 'text/plain'];
                var fileType = file.type;
                if (allowedFormats.includes(fileType) || fileType.startsWith('image/')) {
                    var formData = new FormData();
                    formData.append('myfile', file);
                    formData.append('filetype', type);
                    $.ajax({
                        url: '<?php echo e(route('upload.file')); ?>',
                        type: 'POST',
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function(response) {
                            console.log(response)
                            $(".assignment-content").html('')
                            $(".assignment-content").html(response.text)
                            hideLoader();
                        },
                        error: function() {
                            hideLoader();
                            handleError("Failed To Upload")
                        }
                    });
                } else {
                    handleError("Please upload a file in PDF, PNG, or TXT format.")
                }
            } else {
                handleError("Please upload a file in PDF, PNG, or TXT format.")
            }
        }

        function handleError(text) {
            Swal.fire({
                icon: 'error',
                confirmButtonColor: '#253f50',
                title: 'Error',
                text: text,
            });
        }

        function handleSuccess(text) {
            Swal.fire({
                icon: 'success',
                confirmButtonColor: '#253f50',
                title: 'Success',
                text: text,
            });
        }

        let parsedCSVData = [];

        function handleCSV(file) {
            showLoader();
            const validMimeTypes = [
                "text/csv",
                "application/vnd.ms-excel",
                "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"
            ];
            const fileName = file.name.toLowerCase();
            if (
                !validMimeTypes.includes(file.type) &&
                !fileName.endsWith(".csv")
            ) {
                handleError("Please upload a valid CSV or Excel file.");
                return;
            }
            parseUploadedCSV(file)
                .then((data) => {
                    parsedCSVData = data;
                    const modifiedCSV = convertToCSV(parsedCSVData);
                    const modifiedBlob = new Blob([modifiedCSV], {
                        type: "text/csv"
                    });
                    const modifiedFile = new File([modifiedBlob], file.name, {
                        type: "text/csv"
                    });
                    uploadFile(modifiedFile);
                })
                .catch((error) => {
                    console.error("Error parsing file:", error);
                });
        }

        function parseUploadedCSV(file) {
            return new Promise((resolve, reject) => {
                const reader = new FileReader();
                reader.onload = (event) => {
                    const fileContent = event.target?.result;
                    if (typeof fileContent === "string") {
                        const parsedData = parseCSV(fileContent);
                        resolve(parsedData);
                    } else {
                        reject("Failed to read file");
                    }
                };
                reader.onerror = () => reject("Error reading file");
                reader.readAsText(file);
            });
        }

        const parseCSV = (csvText) => {
            return csvText
                .trim()
                .split("\n")
                .map((row) => row.split(",").map(cell => cell.trim()));
        };

        function convertToCSV(data) {
            return data.map(row => row.join(",")).join("\n");
        }

        function uploadFile(file) {
            const fileNameWithoutExtension = file.name.split('.').slice(0, -1).join('.');
            const fileExists = Array.from(document.querySelectorAll(".rubicFileNames ul li"))
                .some(li => li.textContent.trim() === fileNameWithoutExtension);
            hideLoader();
            if (fileExists) {
                Swal.fire({
                    title: 'File Already Exists',
                    text: `A file named "${fileNameWithoutExtension}" already exists. Would you like to replace the existing file?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#253f50',
                    confirmButtonText: 'Yes, replace the file',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Call the function to replace the file
                        replaceFile(file);
                    }
                });
            } else {
                // Proceed with file upload if no duplicate is found
                proceedWithFileUpload(file);
            }
        }

        function replaceFile(file) {
            showLoader();
            var formData = new FormData();
            formData.append('csvFile', file);
            formData.append('replace', true); // Indicate replacement

            $.ajax({
                url: '<?php echo e(route('upload.csvFile')); ?>',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    'Accept': 'application/json'
                },
                success: function(response) {
                    hideLoader();
                    console.log(response);
                    handleSuccess(response.message);
                    updateGradeUI(response); // Refresh the file list
                },
                error: function(xhr) {
                    console.error("Error replacing file:", xhr);
                    handleError("File replacement failed.");
                }
            });
        }

        function proceedWithFileUpload(file) {
            showLoader();
            var formData = new FormData();
            formData.append('csvFile', file);
            $.ajax({
                url: '<?php echo e(route('upload.csvFile')); ?>',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    'Accept': 'application/json'
                },
                success: function(response) {
                    hideLoader();
                    console.log(response);
                    handleSuccess(response.message);
                    updateGradeUI(response); // Update UI with new file list
                },
                error: function(xhr) {
                    hideLoader();
                    console.error("Error uploading file:", xhr);
                    handleError("File upload failed.");
                }
            });
        }

        function updateGradeUI(response) {
            var fileNames = response.filenames;
            var rubicdiv = $(".rubicFileNames");
            rubicdiv.html(''); // Clear previous content
            let ulContent = '<ul>';
            fileNames.forEach(function(name) {
                ulContent += `<li data-value="grade1">${name}</li>`;
            });
            ulContent += '</ul>';
            rubicdiv.append(ulContent);

            // Reattach the click event listener for newly added <li> items
            rubicdiv.find('li').each(function() {
                $(this).on('click', function(event) {
                    document.getElementById('grade_tab').textContent = $(this).text();
                    $('#dropdown-menu').hide();
                });
            });
        }
        $('.saveInstruction').on('click', function() {
            var courseName = $("#courseName").val();
            var instructions = $("#assignmentInstructions").val();
            var hasError = false;
            $(".error").html('');
            if (courseName == "") {
                $("#courseName").siblings(".error").text("Please enter Course Name");
                hasError = true;
            }
            if (instructions == "") {
                $("#assignmentInstructions").siblings(".error").text(
                    "Please enter Assignment Instructions");
                hasError = true;
            }
            if (!hasError) {
                $("#assignmentModal").modal("hide");
            }
        });
        $('.startAssessment').on('click', function() {
            showLoader();
            var courseName = $("#courseName").val();
            var instructions = $("#assignmentInstructions").val();
            var assignment = $(".assignment-content").val();
            var rubicName = $("#grade_tab").text();
            if (assignment == '') {
                hideLoader();
                handleError("Assignment is empty");
                return;
            }
            if (courseName == "" || instructions == "") {
                hideLoader();
                handleError("Please Insert Assignment Instructions");
                return;
            }
            if (rubicName == "" || rubicName == "Select Grade") {
                hideLoader();
                handleError("Please select rubic");
                return;
            }
            var data = {
                courseName: courseName,
                instructions: instructions,
                assignment: assignment,
                rubicName: rubicName,
            }
            $.ajax({
                url: '<?php echo e(route('start.assesment')); ?>',
                type: 'POST',
                data: data,
                success: function(response) {
                    try {
                        console.log(response);

                        // Validate the API response structure
                        if (
                            response &&
                            response.data &&
                            response.data.choices &&
                            Array.isArray(response.data.choices) &&
                            response.data.choices[0] &&
                            response.data.choices[0].message &&
                            response.data.choices[0].message.content
                        ) {
                            // Extract content and parse it
                            let rawContent = response.data.choices[0].message.content;
                            let sanitizedContent = rawContent
                                .replace(/```json/g, "") // Remove starting code block
                                .replace(/```/g, "");
                            var content = JSON.parse(sanitizedContent);
                            console.log("content", content)

                        } else {
                            throw new Error("Invalid response format from API.");
                        }

                        // Initialize variables for extracted data
                        let feedback = "";
                        let grade = "";
                        let reliabilityIndex = "";
                        let percentage = 0;

                        // Parse content (array of objects)
                        if (Array.isArray(content)) {
                            for (let item of content) {
                                if (item.Criteria) {
                                    feedback += `\n${item.Feedback}\n`; // Append feedback
                                } else if (item.Grade) {
                                    grade = item.Grade;
                                    if (item.Percentage) {
                                        percentage = parseInt(item.Percentage.replace("%",
                                            ""), 10) || 0; // Ensure a valid number
                                    }
                                } else if (item.ReliabilityIndex) {
                                    reliabilityIndex = item.ReliabilityIndex;
                                }
                            }
                        } else {
                            throw new Error("Content is not in the expected format.");
                        }

                        // Update UI components
                        $(".feedback-content").val(feedback);
                        $(".grade").text(grade);
                        $(".percentage").text(percentage);
                        updateChartPercentage(percentage);
                        $(".ai_relaiability").text(reliabilityIndex);
                    } catch (err) {
                        console.error("Error processing response:", err.message);
                        handleError(
                            "An error occurred while processing the response. Please try again later."
                        );
                    } finally {
                        hideLoader();
                    }
                },
                error: function(e) {
                    console.error("Error from API:", e);
                    const errorMessage = e.responseJSON && e.responseJSON.error ? e
                        .responseJSON.error : "An unexpected error occurred.";
                    handleError(errorMessage);
                    hideLoader();
                }
            });
        });

        function updateChartPercentage(percentage) {
            if (gauge) {
                gauge.set(percentage); // Update the gauge with the new percentage
            } else {
                console.error("Gauge object not found!");
            }
        }

        function displayUserInformation() {
            var student = JSON.parse(localStorage.getItem('student_data'));
            if (student.name && $('#name_s').length) {
                $('#name_s').val(student.name);
            }
            if (student.mark && $('#marks_s').length) {
                $('#marks_s').val(student.mark);
            }
            if (student.grade && $('#grade_s').length) {
                $('#grade_s').val(student.grade);
            }
            if (student.comment && $('#comment_s').length) {
                $('#comment_s').val(student.comment);
            }
        }
    });

    function backBtn() {
        const savedFilters = JSON.parse(localStorage.getItem('marksFilters'));
        var students = JSON.parse(localStorage.getItem('student_data'));
        var class_id = savedFilters.class_id;
        var section_id = savedFilters.section_id;
        var subject_id = savedFilters.subject_id;
        var session_id = savedFilters.session_id;
        var exam_category_id = savedFilters.exam_category_id;
        var mark = $('#marks_s').val();
        var comment = $('#comment_s').val();
        var student_id = students.id;
        if (subject_id != "") {
            $.ajax({

                type: 'GET',

                url: "<?php echo e(route('update.mark')); ?>",

                data: {
                    student_id: student_id,
                    class_id: class_id,
                    section_id: section_id,
                    subject_id: subject_id,
                    session_id: session_id,
                    exam_category_id: exam_category_id,
                    mark: mark,
                    comment: comment
                },

                success: function(response) {
                    window.location.href = "<?php echo e(route('teacher.marks')); ?>";

                }

            });

        } else {
            toastr.error('<?php echo e(get_phrase('Required mark field')); ?>');

        }
    }

    function get_grade(exam_mark) {
        let url = "<?php echo e(route('get.grade', ['exam_mark' => ':exam_mark'])); ?>";
        url = url.replace(":exam_mark", exam_mark);
        var student_data = JSON.parse(localStorage.getItem('student_data')) || [];
        $.ajax({
            url: url,
            success: function(response) {
                $('#grade_s').val(response);
                student_data.grade = response;
                student_data.mark = exam_mark;
                localStorage.setItem('student_data', JSON.stringify(student_data));
            }
        });
    }
</script>

<?php echo $__env->make('teacher.navigation', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/moeez/Documents/lms-skoleai-main/resources/views/assignments-preview/index.blade.php ENDPATH**/ ?>