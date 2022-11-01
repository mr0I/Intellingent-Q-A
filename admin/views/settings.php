<?php defined( 'ABSPATH' ) or die( 'No script kiddies please!' ); ?>

<div class="menu-wrapper">
    <input class="radio" id="r_one" name="group" type="radio" checked>
    <input class="radio" id="r_two" name="group" type="radio">

    <div class="tabs">
        <label class="tab" id="tab_one" for="r_one">تنظیمات هدر</label>
        <label class="tab" id="tab_two" for="r_two">افزودن نمایندگی</label>
    </div>

    <div class="panels">
        <div class="panel" id="panel_one">
            <div class="panel-title"><?= __('Add Question and Answer', 'intl_qa_lan') ?></div>
            <form method="post" action="" name="add_qa_frm" id="add_qa_frm">
                <table class="form-table" id="top_menu_links_tbl" role="presentation">
                    <tbody>
                    <tr id="row-1">
                        <td>
                            <textarea class="left-align" name="question"
                                      placeholder="<?= __('Enter Your Question...','intl_qa_lan') ?>" rows="3"></textarea>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <textarea class="left-align" name="answer"
                                      placeholder="<?= __('Enter Your Answer...','intl_qa_lan') ?>" rows="3"></textarea>
                        </td>
                    </tr>
                    <tr><td><input type="text" name="tags" placeholder="sddad..."></td></tr>
                    </tbody>
                </table>
                <p class="submit">
                    <input type="hidden" name="nonce" value="<?= wp_create_nonce('add_qa') ?>">
                    <input type="submit" class="button button-primary pull-left" name="submit" value="<?= __('Save','intl_qa_lan') ?>">
                </p>
            </form>

            <hr>

            <div class="panel-title"><?= __('Stopwords', 'intl_qa_lan') ?></div>
            <form method="post" action="" name="stopwords_frm" id="stopwords_frm">
                <table class="form-table" id="top_menu_links_tbl" role="presentation">
                    <tbody>
                    <tr>
                        <td>
                            <input type="text" name="stopwords" placeholder="Enter Stopwords...">
                        </td>
                    </tr>
                    </tbody>
                </table>
                <span><?= var_dump(get_option('iqa_stopwords')); ?></span>
                <p class="submit">
                    <input type="hidden" name="nonce" value="<?= wp_create_nonce('stopwords') ?>">
                    <input type="submit" class="button button-primary pull-left" name="submit" value="<?= __('Save','intl_qa_lan') ?>">
                </p>
            </form>


        </div>
        <div class="panel" id="panel_two">
            <div class="panel-title">افزودن نمایندگی</div>
            <form method="post" action="" id="" name="agencies_frm">
                <table class="form-table" role="presentation">
                    <tbody>
                    <tr>
                        <th scope="row">استان</th>
                        <td>
                            <select name="city_parent_select" id="city_parent_select" style="max-width: 100%;min-width: 400px">
                                <option value="0">انتخاب استان...</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">شهر</th>
                        <td>
                            <select name="city_name_select" id="city_name_select" style="max-width: 100%;min-width: 400px">
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">عنوان</th>
                        <td>
                            <input class="left-align" type="text" name="title"
                                   style="max-width: 100%;min-width: 400px">
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">فروشگاه</th>
                        <td>
                            <input class=" left-align" type="text" name="shop"
                                   style="max-width: 100%;min-width: 400px" required="">
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">مالک</th>
                        <td>
                            <input class=" left-align" type="text" name="owner"
                                   style="max-width: 100%;min-width: 400px" required="">
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">شماره تماس</th>
                        <td>
                            <input class=" left-align" type="text" name="phone_number"
                                   style="max-width: 100%;min-width: 400px" required="">
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">آدرس</th>
                        <td>
                            <input class=" left-align" type="text" name="address"
                                   style="max-width: 100%;min-width: 400px" required="">
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">نقشه</th>
                        <td>
                            <input class=" left-align" type="text" name="map"
                                   style="max-width: 100%;min-width: 400px" required="">
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">لینک برگه نمایندگی</th>
                        <td>
                            <input class=" left-align" type="text" name="page_url"
                                   style="max-width: 100%;min-width: 400px" required="">
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">گرید</th>
                        <td>
                            <select name="grade" id="" style="max-width: 100%;min-width: 400px">
                                <option value="1">A</option>
                                <option value="2">B</option>
                                <option value="3">C</option>
                                <option value="4">D</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">وضعیت</th>
                        <td>
                            <select name="status" style="max-width: 100%;min-width: 400px">
                                <option value="true">منتشر شده</option>
                                <option value="false">منتشر نشده</option>
                            </select>
                        </td>
                    </tr>
                    </tbody>
                </table>
                <input type="hidden" id="add_agency_nonce" value="<?= wp_create_nonce('add-agency-nonce') ?>">
                <p class="submit"><input type="submit" id="agency_submit_btn" class="button button-primary" value="ذخیره"></p>
            </form>
        </div>
    </div>
</div>