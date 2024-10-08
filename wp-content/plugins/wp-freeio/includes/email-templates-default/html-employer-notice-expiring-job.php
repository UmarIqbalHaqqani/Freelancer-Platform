<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    </head>
    <body style="margin: 0; padding: 0;">
        <div style="background-color: #eeeeef; padding: 50px 0;">
            <table style="max-width: 640px;" border="0" cellspacing="0" cellpadding="0" align="center">
                <tbody>
                    
                    <tr>
                        <td align="center" valign="top"><!-- Body -->
                            <table id="template_body" border="0" width="100%" cellspacing="0" cellpadding="0">
                                <tbody>
                                    <tr>
                                        <td id="mailtpl_body_bg" style="background-color: #fafafa;" valign="top"><!-- Content -->
                                            <table border="0" width="100%" cellspacing="0" cellpadding="20">
                                                <tbody>
                                                    <tr>
                                                        <td valign="top">Hello

                                                            Your Job "{{job_title}}" is Expired you can renew this jobs by login to your Employer account go to Manage Jobs &gt; find your jobs from listings "{{job_title}}" Edit your job select package on step 2 and update your job by this ways your jobs will be publish again.

                                                            Visit the <a href="{{employer_dashboard_url}}">job listing dashboard</a> to manage the listing.

                                                            <h5>Job Information</h5>
                                                            <table class="blueTable">
                                                                
                                                                <tbody>
                                                                    <tr>
                                                                        <td>job title</td>
                                                                        <td>{{job_title}}</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>job type</td>
                                                                        <td>{{job_type}}</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>sector</td>
                                                                        <td>{{job_category}}</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>publish date</td>
                                                                        <td>{{job_publish_date}}</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>expiry date</td>
                                                                        <td>{{job_expiry_date}}</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>featured</td>
                                                                        <td>{{job_featured}}</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>urgent</td>
                                                                        <td>{{job_urgent}}</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>posted by</td>
                                                                        <td>{{author}}</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>status</td>
                                                                        <td>{{job_status}}</td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td align="center" valign="top">
                            <table id="template_footer" style="border-top: 1px solid #E2E2E2; background: #eee; -webkit-border-radius: 0px 0px 6px 6px; -o-border-radius: 0px 0px 6px 6px; -moz-border-radius: 0px 0px 6px 6px; border-radius: 0px 0px 6px 6px;" border="0" width="100%" cellspacing="0" cellpadding="10">
                                <tbody>
                                    <tr>
                                        <td valign="top">
                                            <table border="0" width="100%" cellspacing="0" cellpadding="10">
                                                <tbody>
                                                    <tr>
                                                        <td id="credit" style="border: 0; color: #777; font-family: Arial; font-size: 12px; line-height: 125%; text-align: center;" colspan="2" valign="middle"><a href="{{website_url}}">{{website_name}}</a></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <!-- End Footer --></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </body>
</html>