@extends('teacher.navigation')



@section('content')
    <div class="mainSection-title">

        <div class="row">

            <div class="col-12">
                <!-- Button for external website -->
                <a href="https://skole-ai-7.vercel.app/essay" target="_blank" class="btn btn-primary mt-3">Asses with AI</a>

                <div class="d-flex justify-content-between align-items-center flex-wrap gr-15">

                    <div class="d-flex flex-column">

                        <h4>{{ get_phrase('Manage Marks') }}</h4>

                        <ul class="d-flex align-items-center eBreadcrumb-2">

                            <li><a href="#">{{ get_phrase('Home') }}</a></li>

                            <li><a href="#">{{ get_phrase('Examination') }}</a></li>

                            <li><a href="#">{{ get_phrase('Marks') }}</a></li>

                        </ul>

                    </div>

                </div>

            </div>

        </div>

    </div>



    <div class="row">

        <div class="col-12">

            <div class="eSection-wrap">

                <div class="row">

                    <div class="row justify-content-md-center">

                        <div class="col-md-2">

                            <select class="form-select eForm-select eChoice-multiple-with-remove" id = "exam_category_id"
                                name="exam_category_id">

                                <option value="">{{ get_phrase('Select category') }}</option>

                                @foreach ($exam_categories as $exam_category)
                                    <option value="{{ $exam_category->id }}">{{ $exam_category->name }}</option>
                                @endforeach

                            </select>

                        </div>

                        <div class="col-md-2">

                            <select name="class_id" id="class_id"
                                class="form-select eForm-select eChoice-multiple-with-remove" required
                                onchange="classWiseSection(this.value)">

                                <option value="">{{ get_phrase('Select class') }}</option>

                                @foreach ($classes as $class)
                                    <option value="{{ $class['id'] }}">{{ $class['name'] }}</option>
                                @endforeach

                            </select>

                        </div>



                        <div class="col-md-2">

                            <select name="section_id" id="section_id"
                                class="form-select eForm-select eChoice-multiple-with-remove" required>

                                <option value="">{{ get_phrase('First select a class') }}</option>

                            </select>

                        </div>

                        <div class="col-md-2">

                            <select name="subject_id" id="subject_id"
                                class="form-select eForm-select eChoice-multiple-with-remove" required>

                                <option value="">{{ get_phrase('First select a class') }}</option>

                            </select>

                        </div>

                        <div class="col-md-2">

                            <select name="session_id" id="session_id"
                                class="form-select eForm-select eChoice-multiple-with-remove" required>

                                <option value="">{{ get_phrase('Select a session') }}</option>

                                @foreach ($sessions as $session)
                                    <option value="{{ $session->id }}">{{ $session->session_title }}</option>
                                @endforeach

                            </select>

                        </div>

                        <div class="col-xl-2 mb-3">

                            <button type="button" class="btn btn-icon btn-secondary"
                                onclick="filter_marks()">{{ get_phrase('Filter') }}</button>

                        </div>



                        <div class="card-body marks_content">

                            <div class="empty_box center">

                                <img class="mb-3" width="150px" src="{{ asset('assets/images/empty_box.png') }}" />

                                <br>

                                <span class="">{{ get_phrase('No data found') }}</span>

                            </div>

                        </div>



                    </div>

                </div>

            </div>

        </div>

    </div>
@endsection



<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script type="text/javascript">
    "use strict";




    function classWiseSection(classId, section_id = null, subject_id = null) {

        let url = "{{ route('class_wise_sections', ['id' => ':classId']) }}";

        url = url.replace(":classId", classId);

        $.ajax({

            url: url,

            success: function(response) {

                $('#section_id').html(response);
                $('#section_id').val(section_id);
                classWiseSubect(classId, subject_id);

            }

        });

    }



    function classWiseSubect(classId, subject_id = null) {

        let url = "{{ route('class_wise_subject', ['id' => ':classId']) }}";

        url = url.replace(":classId", classId);

        $.ajax({

            url: url,

            success: function(response) {

                $('#subject_id').html(response);
                $('#subject_id').val(subject_id);
            }

        });

    }



    function filter_marks(exam_category_id = null, class_id = null, section_id = null, subject_id = null, session_id =
        null, functionCall = false) {
        if (functionCall) {
            getFilteredMarks(exam_category_id, class_id, section_id, subject_id, session_id);
        } else {
            var exam_category_id = $('#exam_category_id').val();

            var class_id = $('#class_id').val();

            var section_id = $('#section_id').val();

            var subject_id = $('#subject_id').val();

            var session_id = $('#session_id').val();
            if (exam_category_id != "" && class_id != "" && section_id != "" && subject_id != "" && session_id != "") {
                getFilteredMarks(exam_category_id, class_id, section_id, subject_id, session_id);
            } else {
                toastr.error('{{ get_phrase('Please select all the fields') }}');
            }
        }

    }



    var getFilteredMarks = function(exam_category_id, class_id, section_id, subject_id, session_id) {
        if (exam_category_id != "" && class_id != "" && section_id != "" && subject_id != "" && session_id != "") {

            let url = "{{ route('teacher.marks.list') }}";

            $.ajax({

                url: url,

                headers: {

                    'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')

                },

                data: {
                    exam_category_id: exam_category_id,
                    class_id: class_id,
                    section_id: section_id,
                    subject_id: subject_id,
                    session_id: session_id
                },

                success: function(response) {

                    if (response.status === 'success') {

                        $('.marks_content').html(response.html);
                        localStorage.setItem('marksFilters', JSON.stringify({
                            exam_category_id,
                            class_id,
                            section_id,
                            subject_id,
                            session_id
                        }));
                        localStorage.setItem('marksContent', response.html);

                    } else {

                        toastr.warning(response.message);

                    }

                },

                error: function(xhr, status, error) {

                    // Handle error

                    console.error(error);

                }

            });

        }
    }
    $(document).ready(function() {
        const savedFilters = JSON.parse(localStorage.getItem('marksFilters'));
        if (savedFilters) {
            classWiseSection(savedFilters.class_id, savedFilters.section_id, savedFilters.subject_id);
            $('#exam_category_id').val(savedFilters.exam_category_id);
            $('#class_id').val(savedFilters.class_id);
            $('#session_id').val(savedFilters.session_id);
            var functionCall = true;
            filter_marks(savedFilters.exam_category_id, savedFilters.class_id, savedFilters.section_id,
                savedFilters.subject_id, savedFilters.session_id, functionCall);
        }
        $(document).on('click', '.2ai', function(e) {
            e.preventDefault();
            var id = $(this).data('id');
            var student_name = $('.student_name_' + id).text().trim();
            var student_mark = $('.student_mark_' + id).val();
            var student_grade = $('.student_grade_' + id).text().trim();
            var student_comment = $('.student_coment_' + id).val().trim();
            var student_data = {
                id: id,
                name: student_name,
                mark: student_mark,
                grade: student_grade,
                comment: student_comment
            };
            var student_data = {
                id: id,
                name: student_name,
                mark: student_mark,
                grade: student_grade,
                comment: student_comment
            };

            // Save the single record directly in localStorage
            localStorage.setItem('student_data', JSON.stringify(student_data));
            window.location.href = "{{ route('ai-assignment') }}";
        })

    });
</script>
