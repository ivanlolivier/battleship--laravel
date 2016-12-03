<div class="map">
    <table>
        <tbody>
        @for($i=1; $i<=10; $i++)
            <tr>
                @for($j='a'; $j<='j'; $j++)
                    <td data-pos="{{ $j . $i }}"></td>
                @endfor
            </tr>
        @endfor
        </tbody>
    </table>
</div>