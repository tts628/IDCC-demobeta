<div class="vw-100 new-legacy-content">
    <div class="ps-5 mt-5">
        <div class="row mb-5">
            <div class="ps-2 pb-3 border-bottom">
                <h3 class="fw-bold">能源耗用資料報告</h3>
            </div>
        </div>
        <div class="row my-4">
            <div class="col-4">
                <div class="row">
                    <div class="col-xl-5 col-lg-6 col-md-12 col-sm-12 col-xs-12 col-12 mb-2">
                        <div class="btn-group" role="group" aria-label="Toggle button group">
                            <input type="radio" class="btn-check" name="options" id="monthOption" autocomplete="off" checked>
                            <label class="btn btn-outline-primary" for="monthOption">Month</label>

                            <input type="radio" class="btn-check" name="options" id="dayHiddenOption" autocomplete="off">
                            <label class="btn btn-outline-primary" for="dayHiddenOption" hidden>Hidden Day</label>

                            <input type="radio" class="btn-check" name="options" id="weekOption" autocomplete="off">
                            <label class="btn btn-outline-primary" for="weekOption">Week</label>
                        </div>
                    </div>
                    <div class="col-xl-5 col-lg-6 col-md-12 col-sm-12 col-xs-12 col-12">
                        <button class="btn btn-info" id="setToday" disabled>Today</button>
                    </div>
                </div>
            </div>
            <div class="col-1 text-center">
                <button class="btn btn-dark date-iterator" data-direction="previous"><</button>
            </div>
            <div class="col-2">
                <!-- <button id="checkRadioButton">Check Radio Button</button> -->
                <h4>
                    <div id="dateSelector" data-datetype="month" class="position-relative date-selector-height">
                        <input type="date" class="form-control date-input position-absolute z-1" id="dateInput">
                        <input type="month" class="form-control date-input position-absolute z-1" id="monthInput">
                        <div class="position-absolute text-center" style="width: 100%; height: 100%;">
                            <h5><span id="selectedDate"></span></h5>
                        </div>
                    </div>
                </h4>
            </div>
            <div class="col-1 text-center">
                <button class="btn btn-dark date-iterator" data-direction="next">></button>
            </div>
            <div class="col-4">
                <div class="d-flex">
                    <div class="ms-auto">
                        <div id="loadingSpinner">
                            fetching data... <div class="spinner"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <div id="status"></div>
                <div id="calendarMonth" class="ag-theme-quartz"></div>
                <div id="calendarWeek" class="ag-theme-quartz"></div>
                <div id="calendarDay">
                    <div>
                        <div class="card">
                            <div class="card-header">
                                <h5 id="calendarDayDateText" class="text-center"></h5>
                            </div>
                            <div class="card-body">
                                <div>
                                    Power: <span id="calendarDayPower"></span><br>
                                    Steam: <span id="calendarDaySteam"></span><br>
                                    Water: <span id="calendarDayWater"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>