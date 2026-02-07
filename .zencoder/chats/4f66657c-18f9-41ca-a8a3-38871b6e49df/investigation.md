# Investigation - Bug in admin/peminjaman/show.blade.php

## Bug Summary
The Blade file `resources/views/admin/peminjaman/show.blade.php` (compiled as `31e2ec825ce54a175fd9a73d6815a7ab.php`) has a corrupted structure. An `@endsection` tag at line 269 prematurely closes the `@section('content')`, leaving a large block of code (lines 270-617) outside the rendered section. 

This block contains:
1. Orphaned code residues (lines 270-278) that access `$peminjaman->pengembalian->denda` without checking if `$peminjaman->pengembalian` exists, leading to "Attempt to read property 'denda' on null" errors in logs.
2. A duplicated and more complete "Right Column" (lines 280-396) containing Borrower Info and Admin Actions.
3. Multiple modals (Approve, Reject) and scripts that are also outside the section.
4. A redundant `@endsection` at line 617.

## Root Cause Analysis
The file appears to be a result of a messy merge or incomplete refactoring. The `@endsection` at line 269 was likely inserted by mistake or left behind from a previous version, and the code following it was meant to be part of the main content but became "orphaned". Specifically, the line `{{ number_format($peminjaman->pengembalian->denda, 0, ',', '.') }}` fails because it's executed for loans that aren't yet completed/returned.

## Affected Components
- `resources/views/admin/peminjaman/show.blade.php`
- Admin Loan Detail view

## Proposed Solution
1. Remove the premature `@endsection` at line 269.
2. Remove the orphaned/residue code at lines 270-278.
3. Resolve the duplication between the first Right Column (lines 149-186) and the second one (lines 281-396). The second one seems more complete (includes Actions), but the first one is currently inside the grid.
4. Merge the useful parts of both columns into a single cohesive Right Column within the main grid.
5. Ensure all modals and scripts are correctly placed within the `@section('content')`.
6. Remove the redundant `@endsection` if necessary (though line 617 should be the only one).

Actually, looking at line 187, it ends the grid. 
The second "Right Column" (line 281) starts AFTER the grid has ended. This explains why it's not appearing in the layout correctly.

I will move the "Aksi Admin" and the more detailed "Informasi Peminjam" into the main grid and clean up the rest.

## Implementation Notes
1. Removed the misplaced `@endsection` at line 269 that was causing a large block of code to be rendered outside the content section.
2. Removed orphaned/residue code fragments that were causing runtime errors (accessing `pengembalian->denda` without checks).
3. Merged the duplicated "Right Column" sections into a single, cohesive column inside the main grid.
4. Cleaned up the "Bulk Actions Area" and ensured it has all necessary buttons (Approve, Reject, Handover, Bukti).
5. Added safe logic for displaying "Kembali Aktual" and "Total Denda" for grouped items.
6. Verified that all modals and scripts are correctly placed within the `@section('content')`.
7. Removed the redundant `@endsection` at the end of the file.

