@props(['title','eyebrow'=>'DASHBOARD','copy'=>'Pantau data dan tindak lanjut dalam satu ruang kerja.'])
<div class="mb-[1.5rem] flex flex-col items-start justify-between gap-8 min-[761px]:mb-[2.2rem] min-[761px]:flex-row min-[761px]:items-end">
<div>
<p class="mb-[.65rem] text-[.68rem] font-extrabold tracking-[.18em] text-[var(--pine)]">{{ $eyebrow }}</p>
<h1 class="mb-[.45rem] text-[2.2rem] font-bold leading-none tracking-[-.055em] min-[761px]:text-[clamp(2.1rem,3vw,3.35rem)]">{{ $title }}</h1>
<p class="m-0 text-[.83rem] text-[var(--muted)]">{{ $copy }}</p>
</div>
</div>

