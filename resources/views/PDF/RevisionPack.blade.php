<!DOCTYPE html>
<html>
<head>
    <style>
        @page { margin: 1cm; }
        body { font-family: 'Helvetica', 'Arial', sans-serif; color: #333; line-height: 1.6; }
        
        .header { border-bottom: 3px solid #3498db; margin-bottom: 20px; padding-bottom: 10px; }
        .pupil-info { font-size: 20px; font-weight: bold; color: #2c3e50; }
        
        .topic-card { 
            border: 1px solid #e1e8ed; 
            border-radius: 8px; 
            margin-bottom: 25px; 
            page-break-inside: avoid; 
        }
        .topic-header { 
            background: #f8f9fa; 
            padding: 8px 15px; 
            border-bottom: 1px solid #e1e8ed; 
            position: relative;
        }


        .header-table { width: 100%; border: none; margin: 0; padding: 0; }
        .header-table td { padding: 0; vertical-align: middle; }

        .topic-title { margin: 0; color: #2980b9; font-size: 16px; font-weight: bold; }

        .score-pill-inline { 
            background: #e74c3c; 
            color: white; 
            padding: 2px 10px; 
            border-radius: 12px; 
            font-size: 11px; 
            font-weight: bold;
            white-space: nowrap;
        }
        .topic-title { margin: 0; color: #2980b9; font-size: 18px; }
        
        .content-body { padding: 15px; }
        
        /* The Layout Table */
        .layout-table { width: 100%; border-collapse: collapse; }
        .text-cell { vertical-align: top; width: 75%; }
        .qr-cell { 
            vertical-align: top; 
            width: 25%; 
            text-align: center; 
            padding-left: 15px; 
            border-left: 1px dashed #d1d8dd; 
        }
        
        .qr-image { margin-bottom: 5px; }
        .qr-caption { font-size: 9px; color: #7f8c8d; text-transform: uppercase; letter-spacing: 1px; }
        
        .score-pill { 
            display: inline-block; 
            background: #e74c3c; 
            color: white; 
            padding: 2px 8px; 
            border-radius: 10px; 
            font-size: 12px; 
            margin-bottom: 10px;
        }
    </style>
</head>
<body>

    <div class="header">
        <div class="pupil-info">{{ $pupil->FirstName }} {{ $pupil->Surname }}</div>
        <div style="color: #7f8c8d;">Revision Resource: {{ $subject->Subject }}</div>
        <div style="color: #7f8c8d;">{{ $subject->Subject }} Target Score: {{ $target->Target ?? 'N/A' }}%</div>
    </div>

@foreach ($topicData as $item)
<div class="topic-card">
<div class="topic-header">
    <table class="header-table">
    <tr>
         <td>
             <h3 class="topic-title">{{ $item['topic']->Title }}</h3>
         </td>
        <td style="text-align: right; width: 100px;">
         <span class="score-pill-inline">Score: {{ $item['score'] }}%</span>
        </td>
    </tr>
    </table>
    </div>
    
    <div class="content-body">
        <table class="layout-table">
     <tr>
         <td class="text-cell">
    <div class="rich-text">
            {!! $item['revisionlist'] !!}
    </div>
        </td>
                
         @if(!empty($item['url']))
        <td class="qr-cell">
    <div class="qr-image">
      <img src="data:image/png;base64, {!! base64_encode(QrCode::format('png')->size(85)->margin(0)->generate($item['url'])) !!} ">
     </div>
    <div class="qr-caption">Resource</div>
        </td>
                @endif
         </tr>
        </table>
    </div>
</div>
@endforeach

</body>
</html>