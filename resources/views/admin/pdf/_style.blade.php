<style>
    @font-face {
        font-family: 'THSarabunNew';
        font-style: normal;
        font-weight: normal;
        src: url("{{ public_path('fonts/THSarabunNew.ttf') }}") format('truetype');
    }

    @font-face {
        font-family: 'THSarabunNew';
        font-style: normal;
        font-weight: bold;
        src: url("{{ public_path('fonts/THSarabunNew Bold.ttf') }}") format('truetype');
    }

    * {
        box-sizing: border-box;
        margin: 0;
        padding: 0;
    }

    body {
        font-family: 'THSarabunNew', sans-serif;
        font-size: 16px;
        color: #000000;
        line-height: 1.5;
        background: #ffffff;
    }

    .page {
        padding: 40px 50px;
    }


    .doc-header {
        width: 100%;
        display: table;
        margin-bottom: 6px;
    }

    .col-left {
        display: table-cell;
        width: 60%;
        vertical-align: top;
    }

    .col-right {
        display: table-cell;
        width: 40%;
        vertical-align: top;
        text-align: right;
    }

    .shop-name {
        font-size: 22px;
        font-weight: bold;
        margin-bottom: 2px;
    }

    .doc-type {
        font-size: 22px;
        font-weight: bold;
        margin-bottom: 4px;
        text-decoration: underline;
    }

    .doc-meta p {
        font-size: 15px;
        margin-bottom: 1px;
    }


    .line-thick {
        border: none;
        border-top: 2px solid #000;
        margin: 8px 0 4px 0;
    }

    .line-thin {
        border: none;
        border-top: 1px solid #000;
        margin: 4px 0 8px 0;
    }

    p {
        margin-bottom: 3px;
    }

    h4 {
        font-size: 14px;
        font-weight: bold;
        margin-bottom: 5px;
    }

    .label-bold {
        font-weight: bold;
        font-size: 15px;
    }

    .section-title {
        font-size: 16px;
        font-weight: bold;
        margin: 14px 0 4px 0;
        text-decoration: underline;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 6px;
        font-size: 15px;
    }

    table th {
        border: 1px solid #000;
        padding: 5px 8px;
        text-align: center;
        font-weight: bold;
        background: #f0f0f0;
        color: #000;
    }

    table td {
        border: 1px solid #000;
        padding: 4px 8px;
        color: #000;
    }

    .td-center {
        text-align: center;
    }

    .td-right {
        text-align: right;
    }

    .td-total {
        font-weight: bold;
        background: #f0f0f0;
    }

    table {
        page-break-inside: auto;
    }

    tr {
        page-break-inside: avoid;
        page-break-after: auto;
    }

    .info-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 10px;
        font-size: 15px;
    }

    .info-table td {
        padding: 2px 8px;
        border: none;
        vertical-align: top;
    }

    .info-divider {
        width: 1px;
        background: #ccc;
        padding: 0 !important;
    }

    .summary-block {
        page-break-inside: avoid;
        break-inside: avoid;
    }

    .sum-table-full {
        width: 100%;
        border-collapse: collapse;
        font-size: 15px;
    }

    .sum-table-full td {
        padding: 5px 10px;
        border: 1px solid #000;
    }

    .sum-label-full {
        text-align: right;
        width: 78%;
    }

    .sum-value-full {
        text-align: right;
        width: 22%;
        white-space: nowrap;
    }

    .sum-total-full td {
        font-weight: bold;
        font-size: 17px;
        border-top: 2px solid #000;
    }

    .sign-section {
        page-break-inside: avoid;
        break-inside: avoid;
        width: 100%;
        display: table;
        margin-top: 50px;
        font-size: 15px;
    }

    .sign-cell {
        display: table-cell;
        text-align: center;
        width: 50%;
        padding: 0 20px;
    }

    .sign-cell-right {
        display: table-cell;
        text-align: center;
        width: 50%;
        padding: 0 20px;
    }

    .sign-line {
        display: block;
        border-bottom: 1px solid #000;
        width: 180px;
        margin: 30px auto 4px auto;
    }

    .remark {
        page-break-inside: avoid;
        font-size: 14px;
        margin-top: 16px;
        border-top: 1px dashed #666;
        padding-top: 6px;
    }
</style>