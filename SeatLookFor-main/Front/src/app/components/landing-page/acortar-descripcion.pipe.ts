import { Pipe, PipeTransform } from '@angular/core';

@Pipe({
  name: 'acortarDescripcion'
})
export class AcortarDescripcionPipe implements PipeTransform {

  transform(value: string, wordLimit: number): string {
    if (!value) return '';
    const words = value.trim().split(/\s+/);
    return words.length > wordLimit
      ? words.slice(0, wordLimit).join(' ') + '...'
      : value;
  }

}
