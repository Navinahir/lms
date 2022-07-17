<table class="Section Title" width="100%" style="border: 0;border-collapse: collapse;margin: 0;padding: 0;background-color: #ffffff;">
    <?php $invoice = $invoice['data']['object']; ?>
    <tbody>
        <tr>
            <td class="Content Title-copy Font Font--title" style="border: 0;border-collapse: collapse;margin: 0;padding: 0;-webkit-font-smoothing: antialiased;-moz-osx-font-smoothing: grayscale;width: 472px;font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Ubuntu, sans-serif;mso-line-height-rule: exactly;vertical-align: middle;color: #32325d;font-size: 24px;line-height: 32px;">
                <span dir="ltr">Hey, <?= $UserInfo['full_name'] ?></span>
            </td>
        </tr>
        <tr>
            <td colspan="3" class="Content Title-copy Font Font--title" style="border: 0;border-collapse: collapse;margin: 0;padding: 0;-webkit-font-smoothing: antialiased;-moz-osx-font-smoothing: grayscale;width: 472px;font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Ubuntu, sans-serif;mso-line-height-rule: exactly;vertical-align: middle;color: #32325d;font-size: 15px;line-height: 32px;">
                <span dir="ltr">Please check your invoice.</span>
            </td>
        </tr>
    </tbody>
</table>

<table class="Wrapper" align="center" style="border: 0;border-collapse: collapse;margin: 0 auto !important;padding: 0;max-width: 600px;min-width: 600px;width: 600px;">
    <tbody>
        <tr>
            <td style="border: 0;border-collapse: collapse;margin: 0;padding: 0;-webkit-font-smoothing: antialiased;-moz-osx-font-smoothing: grayscale;">
                <table class="Divider Divider--small Divider--kill" width="100%" style="border: 0;border-collapse: collapse;margin: 0;padding: 0;">
                    <tbody>
                        <tr>
                            <td class="Spacer Spacer--divider" height="20" style="border: 0;border-collapse: collapse;margin: 0;padding: 0;-webkit-font-smoothing: antialiased;-moz-osx-font-smoothing: grayscale;color: #ffffff;font-size: 1px;line-height: 1px;mso-line-height-rule: exactly;">&nbsp;</td>
                        </tr>
                    </tbody>
                </table>
                <div class="Shadow" style="border-bottom-left-radius: 5px;border-bottom-right-radius: 5px;box-shadow: 0 7px 14px 0 rgba(50,50,93,0.10), 0 3px 6px 0 rgba(0,0,0,0.07);">
                    <table dir="ltr" class="Section Header" width="100%" style="border: 0;border-collapse: collapse;margin: 0;padding: 0;background-color: #ffffff;">
                        <tbody>
                            <tr>

                            </tr>
                        </tbody>
                    </table>
                    <table class="Section Title" width="100%" style="border: 0;border-collapse: collapse;margin: 0;padding: 0;background-color: #ffffff;">
                        <tbody>
                            <tr>
                                <td class="Spacer Spacer--gutter" width="64" style="border: 0;border-collapse: collapse;margin: 0;padding: 0;-webkit-font-smoothing: antialiased;-moz-osx-font-smoothing: grayscale;color: #ffffff;font-size: 1px;line-height: 1px;mso-line-height-rule: exactly;">&nbsp;</td>
                                <td class="Content Title-copy Font Font--title" align="center" style="border: 0;border-collapse: collapse;margin: 0;padding: 0;-webkit-font-smoothing: antialiased;-moz-osx-font-smoothing: grayscale;width: 472px;font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Ubuntu, sans-serif;mso-line-height-rule: exactly;vertical-align: middle;color: #32325d;font-size: 24px;line-height: 32px;">
                                    <span dir="ltr">Invoice</span>
                                </td>
                                <td class="Spacer Spacer--gutter" width="64" style="border: 0;border-collapse: collapse;margin: 0;padding: 0;-webkit-font-smoothing: antialiased;-moz-osx-font-smoothing: grayscale;color: #ffffff;font-size: 1px;line-height: 1px;mso-line-height-rule: exactly;">&nbsp;</td>
                            </tr>
                        </tbody>
                    </table>
                    <table class="Section Divider" width="100%" style="border: 0;border-collapse: collapse;margin: 0;padding: 0;background-color: #ffffff;">
                        <tbody>
                            <tr>
                                <td class="Spacer Spacer--divider" height="8" style="border: 0;border-collapse: collapse;margin: 0;padding: 0;-webkit-font-smoothing: antialiased;-moz-osx-font-smoothing: grayscale;color: #ffffff;font-size: 1px;line-height: 1px;mso-line-height-rule: exactly;">&nbsp;</td>
                            </tr>
                        </tbody>
                    </table>
                    <table class="Section Title" width="100%" style="border: 0;border-collapse: collapse;margin: 0;padding: 0;background-color: #ffffff;">
                        <tbody>
                            <tr>
                                <td class="Spacer Spacer--gutter" width="64" style="border: 0;border-collapse: collapse;margin: 0;padding: 0;-webkit-font-smoothing: antialiased;-moz-osx-font-smoothing: grayscale;color: #ffffff;font-size: 1px;line-height: 1px;mso-line-height-rule: exactly;">&nbsp;</td>
                                <td class="Content Title-copy Font Font--title" align="center" style="border: 0;border-collapse: collapse;margin: 0;padding: 0;-webkit-font-smoothing: antialiased;-moz-osx-font-smoothing: grayscale;width: 472px;font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Ubuntu, sans-serif;mso-line-height-rule: exactly;vertical-align: middle;color: #8898aa;font-size: 15px;line-height: 18px;">
                                    Invoice #<?= $invoice['number'] ?>
                                </td>
                                <td class="Spacer Spacer--gutter" width="64" style="border: 0;border-collapse: collapse;margin: 0;padding: 0;-webkit-font-smoothing: antialiased;-moz-osx-font-smoothing: grayscale;color: #ffffff;font-size: 1px;line-height: 1px;mso-line-height-rule: exactly;">&nbsp;</td>
                            </tr>
                        </tbody>
                    </table>
                    <table class="Section Divider" width="100%" style="border: 0;border-collapse: collapse;margin: 0;padding: 0;background-color: #ffffff;">
                        <tbody>
                            <tr>
                                <td class="Spacer Spacer--divider" height="4" style="border: 0;border-collapse: collapse;margin: 0;padding: 0;-webkit-font-smoothing: antialiased;-moz-osx-font-smoothing: grayscale;color: #ffffff;font-size: 1px;line-height: 1px;mso-line-height-rule: exactly;">&nbsp;</td>
                            </tr>
                        </tbody>
                    </table>
                    <table class="Section Divider" width="100%" style="border: 0;border-collapse: collapse;margin: 0;padding: 0;background-color: #ffffff;">
                        <tbody>
                            <tr>
                                <td class="Spacer Spacer--divider" height="24" style="border: 0;border-collapse: collapse;margin: 0;padding: 0;-webkit-font-smoothing: antialiased;-moz-osx-font-smoothing: grayscale;color: #ffffff;font-size: 1px;line-height: 1px;mso-line-height-rule: exactly;">&nbsp;</td>
                            </tr>
                        </tbody>
                    </table>
                    <table class="Section DataBlocks" width="100%" style="border: 0;border-collapse: collapse;margin: 0;padding: 0;background-color: #ffffff;width: 100%;">
                        <tbody>
                            <tr>
                                <td class="Spacer Spacer--gutter" width="64" style="border: 0;border-collapse: collapse;margin: 0;padding: 0;-webkit-font-smoothing: antialiased;-moz-osx-font-smoothing: grayscale;color: #ffffff;font-size: 1px;line-height: 1px;mso-line-height-rule: exactly;">&nbsp;</td>
                                <td class="Content" style="border: 0;border-collapse: collapse;margin: 0;padding: 0;-webkit-font-smoothing: antialiased;-moz-osx-font-smoothing: grayscale;width: 472px;">
                                    <table class="DataBlocks DataBlocks--three" style="border: 0;border-collapse: collapse;margin: 0;padding: 0;width: 100%;">
                                        <tbody>
                                            <tr>
                                                <td class="DataBlocks-item" valign="top" style="border: 0;border-collapse: collapse;margin: 0;padding: 0;-webkit-font-smoothing: antialiased;-moz-osx-font-smoothing: grayscale;">
                                                    <table style="border: 0;border-collapse: collapse;margin: 0;padding: 0;">
                                                        <tbody>
                                                            <tr>
                                                                <td class="Font Font--caption Font--uppercase Font--mute Font--noWrap" style="border: 0;border-collapse: collapse;margin: 0;padding: 0;-webkit-font-smoothing: antialiased;-moz-osx-font-smoothing: grayscale;font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Ubuntu, sans-serif;mso-line-height-rule: exactly;vertical-align: middle;color: #8898aa;font-size: 12px;line-height: 16px;white-space: nowrap;font-weight: bold;text-transform: uppercase;">
                                                                    Amount paid
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td class="Font Font--body Font--noWrap" style="border: 0;border-collapse: collapse;margin: 0;padding: 0;-webkit-font-smoothing: antialiased;-moz-osx-font-smoothing: grayscale;font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Ubuntu, sans-serif;mso-line-height-rule: exactly;vertical-align: middle;color: #525f7f;font-size: 15px;line-height: 24px;white-space: nowrap;">
                                                                    $<?= number_format($invoice['amount_due'] / 100, 2) ?>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </td>
                                                <td class="DataBlocks-item" valign="top" style="border: 0;border-collapse: collapse;margin: 0;padding: 0;-webkit-font-smoothing: antialiased;-moz-osx-font-smoothing: grayscale;">
                                                    <table style="border: 0;border-collapse: collapse;margin: 0;padding: 0;">
                                                        <tbody>
                                                            <tr>
                                                                <td class="Font Font--caption Font--uppercase Font--mute Font--noWrap" style="border: 0;border-collapse: collapse;margin: 0;padding: 0;-webkit-font-smoothing: antialiased;-moz-osx-font-smoothing: grayscale;font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Ubuntu, sans-serif;mso-line-height-rule: exactly;vertical-align: middle;color: #8898aa;font-size: 12px;line-height: 16px;white-space: nowrap;font-weight: bold;text-transform: uppercase;">
                                                                    Date paid
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td class="Font Font--body Font--noWrap" style="border: 0;border-collapse: collapse;margin: 0;padding: 0;-webkit-font-smoothing: antialiased;-moz-osx-font-smoothing: grayscale;font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Ubuntu, sans-serif;mso-line-height-rule: exactly;vertical-align: middle;color: #525f7f;font-size: 15px;line-height: 24px;white-space: nowrap;">
                                                                    <?= date('F d, Y', $invoice['date']) ?>
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
                        </tbody>
                    </table>
                    <table class="Section Divider" width="100%" style="border: 0;border-collapse: collapse;margin: 0;padding: 0;background-color: #ffffff;">
                        <tbody>
                            <tr>
                                <td class="Spacer Spacer--divider" height="32" style="border: 0;border-collapse: collapse;margin: 0;padding: 0;-webkit-font-smoothing: antialiased;-moz-osx-font-smoothing: grayscale;color: #ffffff;font-size: 1px;line-height: 1px;mso-line-height-rule: exactly;">&nbsp;</td>
                            </tr>
                        </tbody>
                    </table>
                    <table class="Section Separator" width="100%" style="border: 0;border-collapse: collapse;margin: 0;padding: 0;background-color: #ffffff;">
                        <tbody>
                            <tr>
                                <td class="Spacer Spacer--gutter" width="64" style="border: 0;border-collapse: collapse;margin: 0;padding: 0;-webkit-font-smoothing: antialiased;-moz-osx-font-smoothing: grayscale;color: #ffffff;font-size: 1px;line-height: 1px;mso-line-height-rule: exactly;">&nbsp;</td>
                                <td class="Spacer" bgcolor="e6ebf1" height="1" style="border: 0;border-collapse: collapse;margin: 0;padding: 0;-webkit-font-smoothing: antialiased;-moz-osx-font-smoothing: grayscale;color: #ffffff;font-size: 1px;line-height: 1px;mso-line-height-rule: exactly;">&nbsp;</td>
                                <td class="Spacer Spacer--gutter" width="64" style="border: 0;border-collapse: collapse;margin: 0;padding: 0;-webkit-font-smoothing: antialiased;-moz-osx-font-smoothing: grayscale;color: #ffffff;font-size: 1px;line-height: 1px;mso-line-height-rule: exactly;">&nbsp;</td>
                            </tr>
                        </tbody>
                    </table>
                    <table class="Section Divider" width="100%" style="border: 0;border-collapse: collapse;margin: 0;padding: 0;background-color: #ffffff;">
                        <tbody>
                            <tr>
                                <td class="Spacer Spacer--divider" height="32" style="border: 0;border-collapse: collapse;margin: 0;padding: 0;-webkit-font-smoothing: antialiased;-moz-osx-font-smoothing: grayscale;color: #ffffff;font-size: 1px;line-height: 1px;mso-line-height-rule: exactly;">&nbsp;</td>
                            </tr>
                        </tbody>
                    </table>
                    <table class="Section Copy" style="border: 0;border-collapse: collapse;margin: 0;padding: 0;background-color: #ffffff;">
                        <tbody>
                            <tr>
                                <td class="Spacer Spacer--gutter" width="64" style="border: 0;border-collapse: collapse;margin: 0;padding: 0;-webkit-font-smoothing: antialiased;-moz-osx-font-smoothing: grayscale;color: #ffffff;font-size: 1px;line-height: 1px;mso-line-height-rule: exactly;">&nbsp;</td>
                                <td class="Content Font Font--caption Font--uppercase Font--mute Delink" style="border: 0;border-collapse: collapse;margin: 0;padding: 0;-webkit-font-smoothing: antialiased;-moz-osx-font-smoothing: grayscale;width: 472px;font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Ubuntu, sans-serif;mso-line-height-rule: exactly;vertical-align: middle;color: #8898aa;font-size: 12px;line-height: 16px;font-weight: bold;text-transform: uppercase;">
                                    Summary
                                </td>
                                <td class="Spacer Spacer--gutter" width="64" style="border: 0;border-collapse: collapse;margin: 0;padding: 0;-webkit-font-smoothing: antialiased;-moz-osx-font-smoothing: grayscale;color: #ffffff;font-size: 1px;line-height: 1px;mso-line-height-rule: exactly;">&nbsp;</td>
                            </tr>
                            <tr>
                                <td class="Spacer Spacer--divider" colspan="3" height="12" style="border: 0;border-collapse: collapse;margin: 0;padding: 0;-webkit-font-smoothing: antialiased;-moz-osx-font-smoothing: grayscale;color: #ffffff;font-size: 1px;line-height: 1px;mso-line-height-rule: exactly;">&nbsp;</td>
                            </tr>
                        </tbody>
                    </table>
                    <table class="Section Table" width="100%" style="border: 0;border-collapse: collapse;margin: 0;padding: 0;background-color: #ffffff;">
                        <tbody>
                            <tr>
                                <td class="Spacer Spacer--kill" width="64" style="border: 0;border-collapse: collapse;margin: 0;padding: 0;-webkit-font-smoothing: antialiased;-moz-osx-font-smoothing: grayscale;color: #ffffff;font-size: 1px;line-height: 1px;mso-line-height-rule: exactly;">&nbsp;</td>
                                <td class="Content" style="border: 0;border-collapse: collapse;margin: 0;padding: 0;-webkit-font-smoothing: antialiased;-moz-osx-font-smoothing: grayscale;width: 472px;">
                                    <table class="Table-body" style="border: 0;border-collapse: collapse;margin: 0;padding: 0;width: 100%;background-color: #f6f9fc;border-radius: 4px;">
                                        <tbody>
                                            <tr>
                                                <td class="Spacer Spacer--divider" colspan="3" height="4" style="border: 0;border-collapse: collapse;margin: 0;padding: 0;-webkit-font-smoothing: antialiased;-moz-osx-font-smoothing: grayscale;color: #ffffff;font-size: 1px;line-height: 1px;mso-line-height-rule: exactly;">&nbsp;</td>
                                            </tr>
                                            <tr>
                                                <td class="Spacer Spacer--gutter" width="20" style="border: 0;border-collapse: collapse;margin: 0;padding: 0;-webkit-font-smoothing: antialiased;-moz-osx-font-smoothing: grayscale;color: #ffffff;font-size: 1px;line-height: 1px;mso-line-height-rule: exactly;">&nbsp;</td>
                                                <td class="Table-content" style="border: 0;border-collapse: collapse;margin: 0;padding: 0;-webkit-font-smoothing: antialiased;-moz-osx-font-smoothing: grayscale;width: 432px;">
                                                    <table class="Table-rows" width="432" style="border: 0;border-collapse: collapse;margin: 0;padding: 0;">
                                                        <tbody>
                                                            <?php
                                                            $total = 0;
                                                            foreach ($invoice['lines']['data'] as $item) {
                                                                $amount = $item['amount'] / 100;
                                                                $total += $amount;
                                                                ?>
                                                                <tr>
                                                                    <td class="Table-divider Spacer" colspan="3" height="6" style="border: 0;border-collapse: collapse;margin: 0;padding: 0;-webkit-font-smoothing: antialiased;-moz-osx-font-smoothing: grayscale;color: #ffffff;font-size: 1px;line-height: 1px;mso-line-height-rule: exactly;">&nbsp;</td>
                                                                </tr>
                                                                <tr>
                                                                    <td class="Table-divider Spacer" colspan="3" height="6" style="border: 0;border-collapse: collapse;margin: 0;padding: 0;-webkit-font-smoothing: antialiased;-moz-osx-font-smoothing: grayscale;color: #ffffff;font-size: 1px;line-height: 1px;mso-line-height-rule: exactly;">&nbsp;</td>
                                                                </tr>
                                                                <tr>
                                                                    <td class="Table-description Font Font--caption Font--uppercase Font--mute Delink" style="border: 0;border-collapse: collapse;margin: 0;padding: 0;-webkit-font-smoothing: antialiased;-moz-osx-font-smoothing: grayscale;font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Ubuntu, sans-serif;mso-line-height-rule: exactly;vertical-align: middle;color: #8898aa;font-size: 12px;line-height: 16px;font-weight: bold;text-transform: uppercase;">
                                                                        <?= date("M d, Y", $item['period']['start']) ?> - <?= date("M d, Y", $item['period']['end']) ?>
                                                                    </td>
                                                                    <td class="Spacer Table-gap" width="8" style="border: 0;border-collapse: collapse;margin: 0;padding: 0;-webkit-font-smoothing: antialiased;-moz-osx-font-smoothing: grayscale;color: #ffffff;font-size: 1px;line-height: 1px;mso-line-height-rule: exactly;">&nbsp;</td>
                                                                    <td class="Spacer Table-gap" style="border: 0;border-collapse: collapse;margin: 0;padding: 0;-webkit-font-smoothing: antialiased;-moz-osx-font-smoothing: grayscale;color: #ffffff;font-size: 1px;line-height: 1px;mso-line-height-rule: exactly;">&nbsp;</td>
                                                                </tr>
                                                                <tr>
                                                                    <td class="Table-divider Spacer" colspan="3" height="6" style="border: 0;border-collapse: collapse;margin: 0;padding: 0;-webkit-font-smoothing: antialiased;-moz-osx-font-smoothing: grayscale;color: #ffffff;font-size: 1px;line-height: 1px;mso-line-height-rule: exactly;">&nbsp;</td>
                                                                </tr>
                                                                <tr>
                                                                    <td class="Table-divider Spacer" colspan="3" height="6" style="border: 0;border-collapse: collapse;margin: 0;padding: 0;-webkit-font-smoothing: antialiased;-moz-osx-font-smoothing: grayscale;color: #ffffff;font-size: 1px;line-height: 1px;mso-line-height-rule: exactly;">&nbsp;</td>
                                                                </tr>
                                                                <tr>
                                                                    <td class="Table-description Font Font--body" style="border: 0;border-collapse: collapse;margin: 0;padding: 0;-webkit-font-smoothing: antialiased;-moz-osx-font-smoothing: grayscale;font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Ubuntu, sans-serif;mso-line-height-rule: exactly;vertical-align: middle;color: #525f7f;font-size: 15px;line-height: 24px;">
                                                                        <div style="">
                                                                            <?= $item['description'] ?>
                                                                        </div>
                                                                    </td>
                                                                    <td class="Spacer Table-gap" width="8" style="border: 0;border-collapse: collapse;margin: 0;padding: 0;-webkit-font-smoothing: antialiased;-moz-osx-font-smoothing: grayscale;color: #ffffff;font-size: 1px;line-height: 1px;mso-line-height-rule: exactly;">&nbsp;</td>
                                                                    <td class="Table-amount Font Font--body" align="right" valign="top" style="border: 0;border-collapse: collapse;margin: 0;padding: 0;-webkit-font-smoothing: antialiased;-moz-osx-font-smoothing: grayscale;font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Ubuntu, sans-serif;mso-line-height-rule: exactly;vertical-align: middle;color: #525f7f;font-size: 15px;line-height: 24px;">
                                                                        $<?= number_format($item['amount'] / 100, 2) ?>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td class="Table-divider Spacer" colspan="3" height="6" style="border: 0;border-collapse: collapse;margin: 0;padding: 0;-webkit-font-smoothing: antialiased;-moz-osx-font-smoothing: grayscale;color: #ffffff;font-size: 1px;line-height: 1px;mso-line-height-rule: exactly;">&nbsp;</td>
                                                                </tr>
                                                                <tr>
                                                                    <td class="Table-divider Spacer" colspan="3" height="6" style="border: 0;border-collapse: collapse;margin: 0;padding: 0;-webkit-font-smoothing: antialiased;-moz-osx-font-smoothing: grayscale;color: #ffffff;font-size: 1px;line-height: 1px;mso-line-height-rule: exactly;">&nbsp;</td>
                                                                </tr>
                                                                <tr>
                                                                    <td class="Spacer" bgcolor="e6ebf1" colspan="3" height="1" style="border: 0;border-collapse: collapse;margin: 0;padding: 0;-webkit-font-smoothing: antialiased;-moz-osx-font-smoothing: grayscale;color: #ffffff;font-size: 1px;line-height: 1px;mso-line-height-rule: exactly;">&nbsp;</td>
                                                                </tr>
                                                                <?php
                                                            }

                                                            $discount = 0;
                                                            $discount_text = '';
                                                            if (isset($invoice['discount'])) {
                                                                if (isset($invoice['discount']['coupon']['percent_off'])) {
                                                                    $discount = $total * ($invoice['discount']['coupon']['percent_off'] / 100);
                                                                    $discount_text = '(' . $invoice['discount']['coupon']['percent_off'] . '% off)';
                                                                } else if (isset($invoice['discount']['coupon']['amount_off'])) {
                                                                    $discount = $invoice['discount']['coupon']['amount_off'] / 100;
                                                                    $discount_text = "($" . number_format($discount, 2) . "off)";
                                                                }
                                                                ?>
                                                                <tr>
                                                                    <td class="Table-divider Spacer" colspan="3" height="6" style="border: 0;border-collapse: collapse;margin: 0;padding: 0;-webkit-font-smoothing: antialiased;-moz-osx-font-smoothing: grayscale;color: #ffffff;font-size: 1px;line-height: 1px;mso-line-height-rule: exactly;">&nbsp;</td>
                                                                </tr>
                                                                <tr>
                                                                    <td class="Table-divider Spacer" colspan="3" height="6" style="border: 0;border-collapse: collapse;margin: 0;padding: 0;-webkit-font-smoothing: antialiased;-moz-osx-font-smoothing: grayscale;color: #ffffff;font-size: 1px;line-height: 1px;mso-line-height-rule: exactly;">&nbsp;</td>
                                                                </tr>
                                                                <tr>
                                                                    <td class="Table-description Font Font--caption Font--uppercase Font--mute Delink" style="border: 0;border-collapse: collapse;margin: 0;padding: 0;-webkit-font-smoothing: antialiased;-moz-osx-font-smoothing: grayscale;font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Ubuntu, sans-serif;mso-line-height-rule: exactly;vertical-align: middle;color: #8898aa;font-size: 12px;line-height: 16px;font-weight: bold;text-transform: uppercase;">
                                                                        Coupon 
                                                                    </td>
                                                                    <td class="Spacer Table-gap" width="8" style="border: 0;border-collapse: collapse;margin: 0;padding: 0;-webkit-font-smoothing: antialiased;-moz-osx-font-smoothing: grayscale;color: #ffffff;font-size: 1px;line-height: 1px;mso-line-height-rule: exactly;">&nbsp;</td>
                                                                    <td class="Spacer Table-gap" style="border: 0;border-collapse: collapse;margin: 0;padding: 0;-webkit-font-smoothing: antialiased;-moz-osx-font-smoothing: grayscale;color: #ffffff;font-size: 1px;line-height: 1px;mso-line-height-rule: exactly;">&nbsp;</td>
                                                                </tr>
                                                                <tr>
                                                                    <td class="Table-divider Spacer" colspan="3" height="6" style="border: 0;border-collapse: collapse;margin: 0;padding: 0;-webkit-font-smoothing: antialiased;-moz-osx-font-smoothing: grayscale;color: #ffffff;font-size: 1px;line-height: 1px;mso-line-height-rule: exactly;">&nbsp;</td>
                                                                </tr>
                                                                <tr>
                                                                    <td class="Table-divider Spacer" colspan="3" height="6" style="border: 0;border-collapse: collapse;margin: 0;padding: 0;-webkit-font-smoothing: antialiased;-moz-osx-font-smoothing: grayscale;color: #ffffff;font-size: 1px;line-height: 1px;mso-line-height-rule: exactly;">&nbsp;</td>
                                                                </tr>
                                                                <tr>
                                                                    <td class="Table-description Font Font--body" style="border: 0;border-collapse: collapse;margin: 0;padding: 0;-webkit-font-smoothing: antialiased;-moz-osx-font-smoothing: grayscale;font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Ubuntu, sans-serif;mso-line-height-rule: exactly;vertical-align: middle;color: #525f7f;font-size: 15px;line-height: 24px;">
                                                                        <div style="">
                                                                            <?= $invoice['discount']['coupon']['id'] ?>  <?= $discount_text ?>
                                                                        </div>
                                                                    </td>
                                                                    <td class="Spacer Table-gap" width="8" style="border: 0;border-collapse: collapse;margin: 0;padding: 0;-webkit-font-smoothing: antialiased;-moz-osx-font-smoothing: grayscale;color: #ffffff;font-size: 1px;line-height: 1px;mso-line-height-rule: exactly;">&nbsp;</td>
                                                                    <td class="Table-amount Font Font--body" align="right" valign="top" style="border: 0;border-collapse: collapse;margin: 0;padding: 0;-webkit-font-smoothing: antialiased;-moz-osx-font-smoothing: grayscale;font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Ubuntu, sans-serif;mso-line-height-rule: exactly;vertical-align: middle;color: #525f7f;font-size: 15px;line-height: 24px;">
                                                                        -$<?= number_format($discount, 2) ?>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td class="Table-divider Spacer" colspan="3" height="6" style="border: 0;border-collapse: collapse;margin: 0;padding: 0;-webkit-font-smoothing: antialiased;-moz-osx-font-smoothing: grayscale;color: #ffffff;font-size: 1px;line-height: 1px;mso-line-height-rule: exactly;">&nbsp;</td>
                                                                </tr>
                                                                <tr>
                                                                    <td class="Table-divider Spacer" colspan="3" height="6" style="border: 0;border-collapse: collapse;margin: 0;padding: 0;-webkit-font-smoothing: antialiased;-moz-osx-font-smoothing: grayscale;color: #ffffff;font-size: 1px;line-height: 1px;mso-line-height-rule: exactly;">&nbsp;</td>
                                                                </tr>
                                                                <tr>
                                                                    <td class="Spacer" bgcolor="e6ebf1" colspan="3" height="1" style="border: 0;border-collapse: collapse;margin: 0;padding: 0;-webkit-font-smoothing: antialiased;-moz-osx-font-smoothing: grayscale;color: #ffffff;font-size: 1px;line-height: 1px;mso-line-height-rule: exactly;">&nbsp;</td>
                                                                </tr>
                                                                <?php
                                                            }
                                                            ?>
                                                            <tr>
                                                                <td class="Table-divider Spacer" colspan="3" height="8" style="border: 0;border-collapse: collapse;margin: 0;padding: 0;-webkit-font-smoothing: antialiased;-moz-osx-font-smoothing: grayscale;color: #ffffff;font-size: 1px;line-height: 1px;mso-line-height-rule: exactly;">&nbsp;</td>
                                                            </tr>
                                                            <tr>
                                                                <td class="Table-divider Spacer" colspan="3" height="6" style="border: 0;border-collapse: collapse;margin: 0;padding: 0;-webkit-font-smoothing: antialiased;-moz-osx-font-smoothing: grayscale;color: #ffffff;font-size: 1px;line-height: 1px;mso-line-height-rule: exactly;">&nbsp;</td>
                                                            </tr>
                                                            <tr>
                                                                <td class="Table-description Font Font--body Font--alt" style="border: 0;border-collapse: collapse;margin: 0;padding: 0;-webkit-font-smoothing: antialiased;-moz-osx-font-smoothing: grayscale;font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Ubuntu, sans-serif;mso-line-height-rule: exactly;vertical-align: middle;color: #525f7f;font-size: 15px;line-height: 24px;font-weight: bold;">
                                                                    Amount paid
                                                                </td>
                                                                <td class="Spacer Table-gap" width="8" style="border: 0;border-collapse: collapse;margin: 0;padding: 0;-webkit-font-smoothing: antialiased;-moz-osx-font-smoothing: grayscale;color: #ffffff;font-size: 1px;line-height: 1px;mso-line-height-rule: exactly;">&nbsp;</td>
                                                                <td class="Table-amount Font Font--body Font--alt" align="right" valign="top" style="border: 0;border-collapse: collapse;margin: 0;padding: 0;-webkit-font-smoothing: antialiased;-moz-osx-font-smoothing: grayscale;font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Ubuntu, sans-serif;mso-line-height-rule: exactly;vertical-align: middle;color: #525f7f;font-size: 15px;line-height: 24px;font-weight: bold;">
                                                                    $<?= number_format($invoice['total'] / 100, 2) ?>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td class="Table-divider Spacer" colspan="3" height="6" style="border: 0;border-collapse: collapse;margin: 0;padding: 0;-webkit-font-smoothing: antialiased;-moz-osx-font-smoothing: grayscale;color: #ffffff;font-size: 1px;line-height: 1px;mso-line-height-rule: exactly;">&nbsp;</td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </td>
                                                <td class="Spacer Spacer--gutter" width="20" style="border: 0;border-collapse: collapse;margin: 0;padding: 0;-webkit-font-smoothing: antialiased;-moz-osx-font-smoothing: grayscale;color: #ffffff;font-size: 1px;line-height: 1px;mso-line-height-rule: exactly;">&nbsp;</td>
                                            </tr>
                                            <tr>
                                                <td class="Spacer Spacer--divider" colspan="3" height="4" style="border: 0;border-collapse: collapse;margin: 0;padding: 0;-webkit-font-smoothing: antialiased;-moz-osx-font-smoothing: grayscale;color: #ffffff;font-size: 1px;line-height: 1px;mso-line-height-rule: exactly;">&nbsp;</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                                <td class="Spacer Spacer--kill" width="64" style="border: 0;border-collapse: collapse;margin: 0;padding: 0;-webkit-font-smoothing: antialiased;-moz-osx-font-smoothing: grayscale;color: #ffffff;font-size: 1px;line-height: 1px;mso-line-height-rule: exactly;">&nbsp;</td>
                            </tr>
                        </tbody>
                    </table>
                    <table class="Section Divider Divider--large" width="100%" style="border: 0;border-collapse: collapse;margin: 0;padding: 0;background-color: #ffffff;">
                        <tbody>
                            <tr>
                                <td class="Spacer Spacer--divider" height="44" style="border: 0;border-collapse: collapse;margin: 0;padding: 0;-webkit-font-smoothing: antialiased;-moz-osx-font-smoothing: grayscale;color: #ffffff;font-size: 1px;line-height: 1px;mso-line-height-rule: exactly;">&nbsp;</td>
                            </tr>
                        </tbody>
                    </table>
                    <table class="Section Section--last Divider Divider--large" width="100%" style="border: 0;border-collapse: collapse;margin: 0;padding: 0;background-color: #ffffff;border-bottom-left-radius: 5px;border-bottom-right-radius: 5px;">
                        <tbody>
                            <tr>
                                <td class="Spacer Spacer--divider" height="64" style="border: 0;border-collapse: collapse;margin: 0;padding: 0;-webkit-font-smoothing: antialiased;-moz-osx-font-smoothing: grayscale;color: #ffffff;font-size: 1px;line-height: 1px;mso-line-height-rule: exactly;">&nbsp;</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </td>
        </tr>
    </tbody>
</table>

<table class="Section Copy" style="border: 0;border-collapse: collapse;margin: 20px 0;padding: 0;background-color: #ffffff;">
    <tbody>
        <tr>
            <td class="Content Footer-help Font Font--body" style="border: 0;border-collapse: collapse;margin: 0;padding: 0;-webkit-font-smoothing: antialiased;-moz-osx-font-smoothing: grayscale;width: 472px;font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Ubuntu, sans-serif;mso-line-height-rule: exactly;vertical-align: middle;color: #525f7f;font-size: 15px;line-height: 24px;">
                <span dir="ltr">Always Reliable Keys</span>,<br>
                <a href="mailto:support@reliablekeys.com" style="white-space: nowrap;-webkit-font-smoothing: antialiased;-moz-osx-font-smoothing: grayscale;outline: 0;text-decoration: none;color: #3297d3;">support@reliablekeys.com</a><br>
                <a href="tel:+12083135332" style="white-space: nowrap;-webkit-font-smoothing: antialiased;-moz-osx-font-smoothing: grayscale;outline: 0;text-decoration: none;color: #3297d3;">+1 208-313-5332</a>
            </td>
        </tr>
    </tbody>
</table>