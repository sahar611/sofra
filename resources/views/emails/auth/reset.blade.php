@component('mail::message')
# Introduction

<p>الكود الخاص بك لتغيير كلمة المرور</p>
<h3>{{$code}}</h3>
<!-- @component('mail::button', ['url' => ''])
Button Text
@endcomponent -->

Thanks,<br>
{{ config('app.name') }}
@endcomponent
